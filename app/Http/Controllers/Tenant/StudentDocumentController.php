<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Installer: composer require barryvdw/laravel-dompdf
use Illuminate\Support\Str;           // Pour Str::slug()

class StudentDocumentController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->get('student_id');
        $documentType = $request->get('document_type');
        $status = $request->get('status');
        
        $documents = StudentDocument::with(['student', 'generator'])
            ->when($studentId, fn($q) => $q->where('student_id', $studentId))
            ->when($documentType, fn($q) => $q->where('document_type', $documentType))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('generated_at', 'desc')
            ->paginate(20);
        
        $students = User::students()->orderBy('name')->get();
        
        return view('tenant.students.documents.index', compact('documents', 'students'));
    }

    // Formulaire de génération de document
    public function generate($studentId)
    {
        $student = User::findOrFail($studentId);
        $schoolYear = SchoolYear::where('is_active', true)->first();
        $currentEnrollment = $student->studentEnrollments()
            ->where('school_year_id', $schoolYear?->id)
            ->latest('enrollment_date')
            ->first();
        
        $documentTypes = StudentDocument::TYPES;
        
        return view('tenant.students.documents.generate', compact(
            'student', 'schoolYear', 'currentEnrollment', 'documentTypes'
        ));
    }

    // Générer et télécharger le document
    public function generateAndDownload(Request $request, $studentId)
    {
        $request->validate([
            'document_type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
        ]);
        
        $student = User::findOrFail($studentId);
        $schoolYear = SchoolYear::where('is_active', true)->first();
        $currentEnrollment = $student->studentEnrollments()
            ->where('school_year_id', $schoolYear?->id)
            ->latest('enrollment_date')
            ->first();
        
        // Générer le contenu HTML du document
        $html = $this->generateDocumentHtml($student, $request->document_type, $currentEnrollment, $schoolYear);
        
        // Générer le PDF
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        
        // Nom du fichier
        $fileName = $this->generateFileName($student, $request->document_type);
        
        // Sauvegarder le PDF
        $filePath = 'documents/' . $student->id . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // Enregistrer dans la base de données
        $document = StudentDocument::create([
            'student_id' => $student->id,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => Storage::disk('public')->size($filePath),
            'mime_type' => 'application/pdf',
            'generated_at' => now(),
            'expires_at' => $request->expires_at,
            'status' => 'published',
            'generated_by' => auth()->id(),
            'metadata' => [
                'student_name' => $student->first_name . ' ' . $student->last_name,  // ← Modification ici
                'document_type_label' => StudentDocument::TYPES[$request->document_type],
                'school_year' => $schoolYear?->name,
                'generated_by_name' => '',
            ],
        ]);
        
        // Télécharger le PDF
        return $pdf->download($fileName);
    }
    
    // Télécharger un document existant
    public function download($id)
    {
        $document = StudentDocument::findOrFail($id);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Fichier introuvable.');
        }
        
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
    
    // Afficher le document en ligne
    public function preview($id)
    {
        $document = StudentDocument::findOrFail($id);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }
        
        $content = Storage::disk('public')->get($document->file_path);
        
        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $document->file_name . '"');
    }
    
    // Supprimer un document
    public function destroy($id)
    {
        $document = StudentDocument::findOrFail($id);
        
        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        
        return redirect()->back()->with('success', 'Document supprimé avec succès');
    }
    
    // Modifier le statut du document
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,published,archived,expired',
        ]);
        
        $document = StudentDocument::findOrFail($id);
        $document->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Statut du document mis à jour');
    }

    // Générer le HTML du document selon le type
    private function generateDocumentHtml($student, $documentType, $currentEnrollment, $schoolYear)
    {
        $date = now();
        $formattedDate = $date->format('d/m/Y');
        $schoolName = tenant('name') ?? config('app.name');
        
        // Informations de l'élève
        $studentName = $student->name;
        $studentBirthDate = $student->date_of_birth?->format('d/m/Y') ?? 'Non renseignée';
        $studentBirthPlace = $student->birth_place ?? 'Non renseigné';
        
        $className = $currentEnrollment?->schoolClass?->name ?? 'Non attribuée';
        $rollNumber = $currentEnrollment?->roll_number ?? 'Non attribué';
        
        // Template de base
        $baseTemplate = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>{$studentName} - " . StudentDocument::TYPES[$documentType] . "</title>
            <style>
                body {
                    font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
                    margin: 0;
                    padding: 40px;
                    background: white;
                    color: #333;
                }
                .document-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    border: 1px solid #ddd;
                    padding: 40px;
                    position: relative;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                }
                .school-logo {
                    width: 80px;
                    height: 80px;
                    margin: 0 auto 15px;
                    background: #f0f0f0;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 40px;
                }
                .school-name {
                    font-size: 24px;
                    font-weight: bold;
                    color: #1a56db;
                    margin-bottom: 5px;
                }
                .school-address {
                    font-size: 12px;
                    color: #666;
                }
                .document-title {
                    text-align: center;
                    font-size: 20px;
                    font-weight: bold;
                    margin: 30px 0;
                    text-transform: uppercase;
                    color: #1f2937;
                }
                .content {
                    margin: 30px 0;
                    line-height: 1.6;
                }
                .student-info {
                    margin: 20px 0;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 20px;
                    background: #f9fafb;
                }
                .info-row {
                    margin-bottom: 10px;
                }
                .info-label {
                    font-weight: bold;
                    width: 180px;
                    display: inline-block;
                }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                }
                .signature {
                    margin-top: 40px;
                    display: flex;
                    justify-content: space-between;
                }
                .signature-line {
                    text-align: center;
                    width: 200px;
                }
                .signature-line .line {
                    margin-top: 40px;
                    border-top: 1px solid #333;
                    width: 100%;
                }
                .qrcode {
                    position: absolute;
                    bottom: 40px;
                    right: 40px;
                    width: 80px;
                    height: 80px;
                    background: #f0f0f0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 10px;
                }
            </style>
        </head>
        <body>
            <div class='document-container'>
                <div class='header'>
                    <div class='school-logo'>🏫</div>
                    <div class='school-name'>" . e($schoolName) . "</div>
                    <div class='school-address'>Document officiel</div>
                </div>
        ";
        
        // Contenu selon le type de document
        $content = $this->getDocumentContent($documentType, $student, $studentName, $studentBirthDate, $studentBirthPlace, $className, $rollNumber, $formattedDate, $schoolYear);
        
        $footer = "
                <div class='footer'>
                    Document généré le {$formattedDate}
                    <br>
                    Ce document est officiel et certifié par l'établissement.
                </div>
                <div class='signature'>
                    <div class='signature-line'>
                        <div class='line'></div>
                        <div>Le parent / tuteur</div>
                    </div>
                    <div class='signature-line'>
                        <div class='line'></div>
                        <div>Le chef d'établissement</div>
                    </div>
                </div>
                <div class='qrcode'>
                    Document<br>validé
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $baseTemplate . $content . $footer;
    }
    
    private function getDocumentContent($type, $student, $name, $birthDate, $birthPlace, $className, $rollNumber, $date, $schoolYear)
    {
        $templates = [
            'certificate_enrollment' => "
                <div class='document-title'>CERTIFICAT DE SCOLARITÉ</div>
                <div class='content'>
                    <p>Je soussigné(e), Chef d'établissement de l'école <strong>" . e(tenant('name') ?? config('app.name')) . "</strong>, certifie que l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Date de naissance :</span> " . e($birthDate) . "</div>
                        <div class='info-row'><span class='info-label'>Lieu de naissance :</span> " . e($birthPlace) . "</div>
                        <div class='info-row'><span class='info-label'>Numéro matricule :</span> " . e($rollNumber) . "</div>
                        <div class='info-row'><span class='info-label'>Classe :</span> <strong>" . e($className) . "</strong></div>
                    </div>
                    <p>est régulièrement inscrit(e) dans notre établissement pour l'année scolaire <strong>" . ($schoolYear?->name ?? date('Y')) . "</strong>.</p>
                    <p>Ce certificat lui est délivré pour servir et valoir ce que de droit.</p>
                </div>
            ",
            
            'attestation_attendance' => "
                <div class='document-title'>ATTESTATION D'ASSIDUITÉ</div>
                <div class='content'>
                    <p>Nous soussignés, l'établissement <strong>" . e(tenant('name') ?? config('app.name')) . "</strong>, attestons que l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                        <div class='info-row'><span class='info-label'>Année scolaire :</span> " . ($schoolYear?->name ?? date('Y')) . "</div>
                    </div>
                    <p>a fait preuve d'une assiduité remarquable tout au long de l'année scolaire.</p>
                    <p>Cette attestation lui est délivrée pour valoriser son implication et sa régularité.</p>
                </div>
            ",
            
            'certificate_achievement' => "
                <div class='document-title'>CERTIFICAT DE MÉRITE</div>
                <div class='content'>
                    <p>Cette attestation est décernée à :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                    </div>
                    <p>pour ses excellentes performances académiques et son comportement exemplaire au sein de notre établissement.</p>
                    <p>Nous lui souhaitons plein de succès dans la poursuite de ses études.</p>
                </div>
            ",
            
            'attestation_behavior' => "
                <div class='document-title'>ATTESTATION DE BONNE CONDUITE</div>
                <div class='content'>
                    <p>Nous attestons que l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                    </div>
                    <p>a fait preuve d'un comportement irréprochable durant toute sa scolarité dans notre établissement.</p>
                    <p>Cette attestation lui est délivrée pour servir et valoir ce que de droit.</p>
                </div>
            ",
            
            'certificate_transfer' => "
                <div class='document-title'>CERTIFICAT DE RADIATION</div>
                <div class='content'>
                    <p>Nous certifions que l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                        <div class='info-row'><span class='info-label'>Numéro matricule :</span> " . e($rollNumber) . "</div>
                    </div>
                    <p>a été radié(e) de notre établissement le {$date}.</p>
                    <p>Ce certificat lui est délivré pour valider son départ et sa radiation administrative.</p>
                </div>
            ",
            
            'attestation_payment' => "
                <div class='document-title'>ATTESTATION DE PAIEMENT</div>
                <div class='content'>
                    <p>Nous attestons que l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                    </div>
                    <p>s'est acquitté(e) de l'intégralité des frais de scolarité pour l'année scolaire <strong>" . ($schoolYear?->name ?? date('Y')) . "</strong>.</p>
                    <p>Sa situation financière est à jour auprès de notre établissement.</p>
                </div>
            ",
            
            'certificate_level' => "
                <div class='document-title'>CERTIFICAT DE NIVEAU</div>
                <div class='content'>
                    <p>Nous certifions que l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Date de naissance :</span> " . e($birthDate) . "</div>
                        <div class='info-row'><span class='info-label'>Niveau atteint :</span> <strong>" . e($className) . "</strong></div>
                    </div>
                    <p>a validé le niveau d'études correspondant à sa classe, avec les compétences requises.</p>
                    <p>Ce certificat atteste de son niveau académique.</p>
                </div>
            ",
            
            'report_card' => "
                <div class='document-title'>BULLETIN DE NOTES</div>
                <div class='content'>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Élève :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                        <div class='info-row'><span class='info-label'>Période :</span> " . ($schoolYear?->name ?? date('Y')) . "</div>
                    </div>
                    <p>Veuillez consulter les résultats détaillés auprès du secrétariat ou sur la plateforme en ligne.</p>
                </div>
            ",
            
            'other' => "
                <div class='document-title'>DOCUMENT OFFICIEL</div>
                <div class='content'>
                    <p>Ce document est délivré à l'élève :</p>
                    <div class='student-info'>
                        <div class='info-row'><span class='info-label'>Nom et prénom :</span> <strong>" . e($name) . "</strong></div>
                        <div class='info-row'><span class='info-label'>Classe :</span> " . e($className) . "</div>
                        <div class='info-row'><span class='info-label'>Date :</span> {$date}</div>
                    </div>
                    <p>Il atteste de sa scolarité dans notre établissement.</p>
                </div>
            ",
        ];
        
        return $templates[$type] ?? $templates['other'];
    }
    
    private function generateFileName($student, $documentType)
    {
        $typeSlug = str_replace('_', '-', $documentType);
        $date = now()->format('Y-m-d');
        $studentSlug = Str::slug($student->name);
        
        return "{$typeSlug}_{$studentSlug}_{$date}.pdf";
    }
}