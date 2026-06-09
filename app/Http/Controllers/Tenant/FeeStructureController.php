<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\FeeStructure;
use App\Models\SchoolYear;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index(Request $request)
    {
        $schoolYearId = $request->get('school_year_id', SchoolYear::where('is_active', true)->first()?->id);
        $classId = $request->get('class_id');
        
        $feeStructures = FeeStructure::with(['schoolYear', 'class'])
            ->when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(20);
        
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('name')
            ->get();
        
        return view('tenant.fees.structures.index', compact('feeStructures', 'schoolYears', 'classes', 'schoolYearId', 'classId'));
    }
    
    public function create()
    {
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $types = FeeStructure::getTypes();
        
        return view('tenant.fees.structures.create', compact('schoolYears', 'classes', 'types'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(FeeStructure::getTypes())),
            'amount' => 'required|numeric|min:0',
            'month' => 'nullable|integer|between:1,12',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        FeeStructure::create($validated);
        
        return redirect()->route('fees.structures.index')
            ->with('success', 'Structure de frais créée avec succès');
    }
    
    public function edit($id)
    {
        $feeStructure = FeeStructure::findOrFail($id);       
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $types = FeeStructure::getTypes();
        
        return view('tenant.fees.structures.edit', compact('feeStructure', 'schoolYears', 'classes', 'types'));
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(FeeStructure::getTypes())),
            'amount' => 'required|numeric|min:0',
            'month' => 'nullable|integer|between:1,12',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $feeStructure = FeeStructure::findOrFail($id);  
        
        $feeStructure->update($validated);
        
        return redirect()->route('fees.structures.index')
            ->with('success', 'Structure de frais mise à jour avec succès');
    }
    
    public function destroy($id)
    {
        $feeStructure = FeeStructure::findOrFail($id);
        $feeStructure->delete();
        
        return redirect()->route('fees.structures.index')
            ->with('success', 'Structure de frais supprimée avec succès');
    }
    
    public function generateForClass(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'class_id' => 'required|exists:school_classes,id',
        ]);
        
        // Logique pour générer automatiquement les frais pour une classe
        // Exemple: frais d'inscription + 10 mensualités
        
        return redirect()->back()->with('success', 'Frais générés avec succès');
    }
}