<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\SchoolClass;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with(['year', 'assignments', 'students'])
            ->withCount(['assignments', 'students'])
            ->when($request->search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->year, function($query, $yearId) {
                return $query->where('school_year_id', $yearId);
            })
            ->orderBy('name');

        $classes = $query->paginate(20)->withQueryString();

        // Get school years for filter
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')
            ->get();

        return view('tenant.classes.index', compact('classes', 'schoolYears'));
    }

    public function create()
    {
        $schoolYears = SchoolYear::get();
        return view('tenant.classes.create', compact('schoolYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'school_year_id' => $request->school_year_id,
        ]);

        return redirect('/classes')->with('success', 'Classe créée avec succès.');
    }

    public function show($id)
    {        
        // Charger les relations nécessaires
        $schoolClass = SchoolClass::findOrFail($id);

        $schoolClass->load([
            'year',
            'assignments' => function($query) {
                $query->with(['subject', 'teacher']);
            },
            'students' => function($query) {
                $query->orderBy('first_name')->orderBy('last_name');
            }
        ]);
        
        // Stats pour les affectations
        $assignmentStats = [
            'total' => $schoolClass->assignments()->count(),
            'active' => $schoolClass->assignments()->where('is_active', true)->count(),
            'hours_per_week' => $schoolClass->assignments()->sum('hours_per_week'),
            'teachers_count' => $schoolClass->assignments()->whereNotNull('teacher_id')->distinct('teacher_id')->count(),
        ];
        return view('tenant.classes.show', compact('schoolClass', 'assignmentStats'));
    }

    public function edit($id)
    {
        $schoolYears = SchoolYear::get();
        $schoolClass = SchoolClass::findOrFail($id);
        return view('tenant.classes.edit', compact('schoolClass', 'schoolYears'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $schoolClass = SchoolClass::findOrFail($id);
        $schoolClass->update($request->only('name', 'school_year_id'));

        return redirect('/classes')->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy($tenant, SchoolClass $schoolClass)
    {
        $schoolClass->delete();
        return back()->with('success', 'Classe supprimée avec succès.');
    }
}