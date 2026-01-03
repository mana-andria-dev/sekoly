<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use Illuminate\Http\Request;

class TeacherAvailabilityController extends Controller
{
    public function index(Teacher $teacher)
    {
        $availabilities = $teacher->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi'
        ];

        return view('tenant.teachers.availabilities.index', compact('teacher', 'availabilities', 'days'));
    }

    public function store($tenant, Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_recurring' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        $availability = new TeacherAvailability($validated);
        $availability->teacher_id = $teacher->id;
        $availability->save();

        return back()->with('success', 'Disponibilité ajoutée.');
    }

    public function destroy($tenant, Teacher $teacher, TeacherAvailability $availability)
    {
        $availability->delete();
        return back()->with('success', 'Disponibilité supprimée.');
    }
}

