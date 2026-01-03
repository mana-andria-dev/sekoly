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
        return view('tenant.school-years.index', [
            'years' => SchoolYear::where('tenant_id', app('tenant')->id)->get()
        ]);
    }

    public function create()
    {
        return view('tenant.school-years.create', [
            'periodTypes' => PeriodType::all(),
        ]);
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

        // for ($i = 1; $i <= $schoolYear->periodType->period_count; $i++) {
        //     $schoolYear->periods()->create([
        //         'name'  => 'Période ' . $i,
        //         'order' => $i,
        //     ]);
        // }        

        return back()->with('success', 'Année scolaire activée');
    }
}
