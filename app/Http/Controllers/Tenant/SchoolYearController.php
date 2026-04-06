<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\PeriodType;

class SchoolYearController extends Controller
{
    public function index()
    {
        $years = SchoolYear::where('tenant_id', app('tenant')->id)
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('tenant.school-years.index', compact('years'));
    }

    public function create()
    {
        $periodTypes = PeriodType::all();
        return view('tenant.school-years.create', compact('periodTypes'));
    }  

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'period_type_id' => 'required|exists:period_types,id',
        ]);

        [$startYear, $endYear] = explode('-', $request->name);

        $startDate = now()->setDate($startYear, 9, 1);
        $endDate   = now()->setDate($endYear, 7, 31);        

        $tenant = app('tenant');

        // Désactiver toutes les autres années
        SchoolYear::where('tenant_id', $tenant->id)
            ->update(['is_active' => false]);

        $schoolYear = SchoolYear::create([
            'tenant_id'      => $tenant->id,
            'name'           => $request->name,
            'period_type_id' => $request->period_type_id,
            'start_date'     => $startDate,
            'end_date'       => $endDate,
            'is_active'      => true,
        ]);

        return redirect()->route('school-years.index', $tenant->name)
            ->with('success', 'Année scolaire créée et activée avec succès');
    }
    
    public function edit($tenant, SchoolYear $schoolYear)
    {
        $this->authorizeTenant($schoolYear);
        
        $periodTypes = PeriodType::all();
        
        return view('tenant.school-years.edit', compact('schoolYear', 'periodTypes'));
    }
    
    public function update(Request $request, $tenant, SchoolYear $schoolYear)
    {
        $this->authorizeTenant($schoolYear);
        
        $request->validate([
            'name' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'period_type_id' => 'required|exists:period_types,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);
        
        $schoolYear->update($request->only(['name', 'period_type_id', 'start_date', 'end_date']));
        
        return redirect()->route('school-years.index', $tenant)
            ->with('success', 'Année scolaire mise à jour avec succès');
    }
    
    public function destroy($tenant, SchoolYear $schoolYear)
    {
        $this->authorizeTenant($schoolYear);
        
        if ($schoolYear->is_active) {
            return redirect()->back()->with('error', 'Impossible de supprimer l\'année scolaire active');
        }
        
        $schoolYear->delete();
        
        return redirect()->route('school-years.index', $tenant)
            ->with('success', 'Année scolaire supprimée avec succès');
    }
    
    public function activate($tenant, SchoolYear $schoolYear)
    {
        $this->authorizeTenant($schoolYear);
        
        // Désactiver toutes les années
        SchoolYear::where('tenant_id', $tenant)->update(['is_active' => false]);
        
        // Activer l'année sélectionnée
        $schoolYear->update(['is_active' => true]);
        
        return redirect()->back()->with('success', 'Année scolaire activée avec succès');
    }
    
    private function authorizeTenant($model)
    {
        if ($model->tenant_id !== app('tenant')->id) {
            abort(403);
        }
    }
}