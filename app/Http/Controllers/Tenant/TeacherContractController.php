<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherContract;
use Illuminate\Http\Request;

class TeacherContractController extends Controller
{
    public function index($tenant, Teacher $teacher)
    {
        $contracts = $teacher->contracts()->orderBy('start_date', 'desc')->paginate(10);
        return view('tenant.teachers.contracts.index', compact('teacher', 'contracts'));
    }

    public function create($tenant, Teacher $teacher)
    {
        return view('tenant.teachers.contracts.create', compact('teacher'));
    }

    public function store($tenant, Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'contract_type' => 'required|in:CDI,CDD,Vacataire,Contractuel',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'hours_per_week' => 'nullable|integer|min:1|max:60',
            'terms' => 'nullable|array',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Générer numéro de contrat
        $contractNumber = 'CONTR-' . date('Y') . '-' . str_pad(TeacherContract::count() + 1, 5, '0', STR_PAD_LEFT);

        $contract = new TeacherContract($validated);
        $contract->teacher_id = $teacher->id;
        $contract->contract_number = $contractNumber;
        $contract->status = 'active';

        // Upload du document
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('contracts', 'public');
            $contract->document_path = $path;
        }

        $contract->save();

        return redirect()->route('teachers.show', [
            'tenant' => app('tenant')->name,
            'teacher' => $teacher->id
        ])->with('success', 'Contrat créé avec succès.');
    }

    public function destroy($tenant, Teacher $teacher, TeacherContract $contract)
    {
        if ($contract->status === 'active') {
            return back()->with('error', 'Impossible de supprimer un contrat actif.');
        }

        $contract->delete();
        return back()->with('success', 'Contrat supprimé.');
    }
}