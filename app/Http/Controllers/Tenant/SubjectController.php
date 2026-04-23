<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Requests\SubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{

    public function __construct()
    {
        \Log::info('SubjectController constructor called');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Stats pour la sidebar
        $stats = [
            'total' => Subject::forTenant()->count(),
            'active' => Subject::forTenant()->active()->count(),
            'maternelle' => Subject::forTenant()->byLevel('maternelle')->count(),
            'primaire' => Subject::forTenant()->byLevel('primaire')->count(),
            'college' => Subject::forTenant()->byLevel('college')->count(),
            'lycee' => Subject::forTenant()->byLevel('lycee')->count(),
        ];

        // Récupération des matières avec filtres
        $subjects = Subject::forTenant()
            ->withCount(['teachers', 'classes'])
            ->search($request->search)
            ->byLevel($request->level)
            ->when($request->has('active'), function($query) use ($request) {
                return $query->where('is_active', $request->boolean('active'));
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $levels = [
            'maternelle' => 'Maternelle',
            'primaire' => 'Primaire',
            'college' => 'Collège',
            'lycee' => 'Lycée'
        ];

        return view('tenant.subjects.index', compact('subjects', 'stats', 'levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $levels = [
            '' => 'Non spécifié',
            'maternelle' => 'Maternelle',
            'primaire' => 'Primaire',
            'college' => 'Collège',
            'lycee' => 'Lycée'
        ];

        return view('tenant.subjects.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        // Si le code est généré automatiquement, le retirer des données validées
        $validatedData = $request->validated();
        
        // Retirer le code des données validées s'il est null
        if (empty($validatedData['code'])) {
            unset($validatedData['code']);
        }
        
        $code = $request->code;
        if (empty($code)) {
            $code = Subject::generateCode($request->name);
        }
        
        $subject = Subject::create([
            'code' => strtoupper($code),
            'name' => $request->name,
            'description' => $request->description,
            'level' => $request->level,
            'hours_per_week' => 1,
            'coefficient' => $request->coefficient,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect('/subjects')->with('success', 'Matière créée avec succès.');
    }

    public function show($id)
    {
        \Log::info('Show method called', [
            'id' => $id,
            'url' => request()->url(),
            'method' => request()->method()
        ]);
        
        try {
            $subject = Subject::findOrFail($id);
            \Log::info('Subject found', ['subject_id' => $subject->id, 'name' => $subject->name]);

            // $subject->load([
            //     'teachers' => function($query) {
            //         $query->select('users.id', 'users.first_name', 'users.last_name', 'users.email');
            //     },
            //     'classes' => function($query) {
            //         $query->with('year')->select('school_classes.id', 'school_classes.name', 'school_classes.school_year_id');
            //     }
            // ]);

            return view('tenant.subjects.show', compact('subject'));            
            
            // ... reste du code
        } catch (\Exception $e) {
            \Log::error('Subject not found', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('subjects.index')->with('error', 'Matière non trouvée');
        }
    }
    
    public function edit($id)  // ← Supprimer le paramètre $tenant
    {
        $levels = [
            '' => 'Non spécifié',
            'maternelle' => 'Maternelle',
            'primaire' => 'Primaire',
            'college' => 'Collège',
            'lycee' => 'Lycée'
        ];
        $subject = Subject::findOrFail($id);

        return view('tenant.subjects.edit', compact('subject', 'levels'));
    }
    
    public function update(Request $request, $id)  // ← Supprimer $tenant
    {
        $code = $request->code;
        // if (empty($code)) {
        //     $code = Subject::generateCode($request->name);
        // }
        $subject = Subject::findOrFail($id);
        $subject->update([
            'code' => strtoupper($code),
            'name' => $request->name,
            'description' => $request->description,
            'level' => $request->level,
            'hours_per_week' => 1,
            'coefficient' => $request->coefficient,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Matière mise à jour avec succès.');
    }
    
    public function destroy($id)  // ← Supprimer $tenant
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        
        return redirect()->route('subjects.index')
            ->with('success', 'Matière supprimée avec succès.');
    }
    
    public function toggleActive(Subject $subject)  // ← Supprimer $tenant
    {
        $subject->is_active = !$subject->is_active;
        $subject->save();
        
        $status = $subject->is_active ? 'activée' : 'désactivée';
        
        return redirect()->route('subjects.index')
            ->with('success', "Matière {$status} avec succès.");
    }

    /**
     * Search subjects for API
     */
    public function search($tenant, Request $request)
    {
        $subjects = Subject::forTenant()
            ->active()
            ->when($request->q, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->level, function($query, $level) {
                return $query->where('level', $level);
            })
            ->limit(10)
            ->get(['id', 'code', 'name', 'level']);

        return response()->json($subjects);
    }
}