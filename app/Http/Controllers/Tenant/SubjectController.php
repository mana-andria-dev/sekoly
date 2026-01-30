<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Requests\SubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
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
        $code = $request->code;
        if (empty($code)) {
            $code = Subject::generateCode($request->name);
        }

        $subject = Subject::create([
            'tenant_id' => app('tenant')->id,
            'code' => strtoupper($code),
            'name' => $request->name,
            'description' => $request->description,
            'level' => $request->level,
            'hours_per_week' => $request->hours_per_week,
            'coefficient' => $request->coefficient,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/subjects')->with('success', 'Matière créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($tenant, Subject $subject)
    {
        // $this->authorize('view', $subject);
        
        $subject->load([
            'teachers' => function($query) {
                $query->select('users.id', 'users.first_name', 'users.last_name', 'users.email');
            },
            'classes' => function($query) {
                $query->with('year')->select('school_classes.id', 'school_classes.name', 'school_classes.school_year_id');
            }
        ]);

        return view('tenant.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tenant, Subject $subject)
    {
        // $this->authorize('update', $subject);
        
        $levels = [
            '' => 'Non spécifié',
            'maternelle' => 'Maternelle',
            'primaire' => 'Primaire',
            'college' => 'Collège',
            'lycee' => 'Lycée'
        ];

        return view('tenant.subjects.edit', compact('subject', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($tenant, SubjectRequest $request, Subject $subject)
    {
        // $this->authorize('update', $subject);

        $subject->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'description' => $request->description,
            'level' => $request->level,
            'hours_per_week' => $request->hours_per_week,
            'coefficient' => $request->coefficient,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/subjects')
            ->with('success', 'Matière mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tenant, Subject $subject)
    {
        // $this->authorize('delete', $subject);

        // Vérifier si la matière est utilisée
        if ($subject->teachers()->count() > 0) {
            return redirect()
                ->route('subjects.index')
                ->with('error', 'Impossible de supprimer cette matière car elle est assignée à des professeurs.');
        }

        if ($subject->classes()->count() > 0) {
            return redirect()
                ->route('subjects.index')
                ->with('error', 'Impossible de supprimer cette matière car elle est assignée à des classes.');
        }

        $subject->delete();

        return redirect('/subjects')
            ->with('success', 'Matière supprimée avec succès.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive($tenant, Subject $subject)
    {
        // $this->authorize('update', $subject);

        $subject->update([
            'is_active' => !$subject->is_active
        ]);

        $status = $subject->is_active ? 'activée' : 'désactivée';
        
        return back()->with('success', "Matière {$status} avec succès.");
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