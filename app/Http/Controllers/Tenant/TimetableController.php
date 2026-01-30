<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\ClassAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $query = Timetable::where('tenant_id', app('tenant')->id)
            ->with(['class', 'academicYear', 'creator'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'true');
        }

        $timetables = $query->paginate(20);
        
        // Pour les filtres
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)->get();
        $academicYears = SchoolYear::where('is_active', true)->get();

        return view('tenant.timetables.index', compact('timetables', 'classes', 'academicYears'));
    }

    public function create()
    {
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->whereHas('year', function($query) {
                $query->where('is_active', true);
            })
            ->get();

        $academicYears = SchoolYear::where('is_active', true)->get();
        
        return view('tenant.timetables.create', compact('classes', 'academicYears'));
    }

    public function store($tenat, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:school_classes,id',
            'academic_year_id' => 'required|exists:school_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'nullable|in:weekly,daily,custom',
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = true;

        $timetable = Timetable::create($validated);

        // Générer automatiquement à partir des affectations
        if ($request->boolean('generate_from_assignments')) {
            $slotsCreated = $timetable->generateFromAssignments();
            
            return redirect()->route('timetables.show', [
                'tenant' => app('tenant')->name,
                'timetable' => $timetable->id
            ])->with('success', "Emploi du temps créé avec {$slotsCreated} créneaux générés automatiquement.");
        }

        return redirect()->route('timetables.show', [
            'tenant' => app('tenant')->name,
            'timetable' => $timetable->id
        ])->with('success', 'Emploi du temps créé avec succès.');
    }

    /*
    public function show($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $timetable->load([
            'class', 
            'academicYear', 
            'creator',
            'timetableSlots.subject',
            'timetableSlots.teacher',
            'timetableSlots.classroom',
            'timetableSlots.teacherProfile'
        ]);
        
        // Organiser les créneaux par jour
        $slotsByDay = $timetable->timetableSlots->groupBy('day_of_week');
        
        // Récupérer les affectations non planifiées
        $unassignedAssignments = ClassAssignment::where('tenant_id', app('tenant')->id)
            ->where('class_id', $timetable->class_id)
            ->where('is_active', true)
            ->whereNotIn('id', $timetable->timetableSlots->pluck('assignment_id')->filter())
            ->with(['subject', 'teacher'])
            ->get();

        // Vérifier les conflits
        $conflicts = $timetable->checkConflicts();
        
        // Calculer les statistiques
        $subjectHours = $timetable->getSubjectHours();
        $totalHours = $subjectHours->sum('total_hours');
        $totalSlots = $timetable->timetableSlots->count();

        return view('tenant.timetables.show', compact(
            'timetable', 
            'slotsByDay', 
            'unassignedAssignments',
            'conflicts',
            'subjectHours',
            'totalHours',
            'totalSlots'
        ));
    }
    */

    public function show($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $timetable->load([
            'class', 
            'academicYear', 
            'creator',
            'timetableSlots.subject',
            'timetableSlots.teacher',
            'timetableSlots.classroom',
            'timetableSlots.teacherProfile'
        ]);
        
        // Organiser les créneaux par jour
        $slotsByDay = $timetable->timetableSlots->groupBy('day_of_week');
        
        // Récupérer TOUTES les affectations de la classe
        $allAssignments = ClassAssignment::where('tenant_id', app('tenant')->id)
            ->where('class_id', $timetable->class_id)
            ->where('is_active', true)
            ->with(['subject', 'teacher'])
            ->get();

        // Calculer les heures déjà planifiées par assignment_id
        $plannedHoursByAssignment = $timetable->timetableSlots
            ->whereNotNull('assignment_id')
            ->groupBy('assignment_id')
            ->map(function($slots) {
                return $slots->sum('duration');
            });

        // Filtrer les affectations non planifiées et calculer les heures restantes
        $unassignedAssignments = $allAssignments->map(function($assignment) use ($plannedHoursByAssignment) {
            $plannedHours = $plannedHoursByAssignment[$assignment->id] ?? 0;
            $remainingHours = max(0, $assignment->hours_per_week - $plannedHours);
            
            return (object)[
                'id' => $assignment->id,
                'assignment' => $assignment,
                'planned_hours' => $plannedHours,
                'remaining_hours' => $remainingHours,
                'is_fully_planned' => $plannedHours >= $assignment->hours_per_week,
                'is_partially_planned' => $plannedHours > 0 && $plannedHours < $assignment->hours_per_week,
                'is_not_planned' => $plannedHours == 0
            ];
        })->filter(function($item) {
            // Garder seulement les affectations qui ne sont pas complètement planifiées
            return $item->remaining_hours > 0;
        })->values();

        // Vérifier les conflits
        $conflicts = $timetable->checkConflicts();
        
        // Organiser les conflits par jour et par heure pour faciliter l'affichage
        $conflictsByDayAndTime = collect();
        
        if ($conflicts->isNotEmpty()) {
            foreach ($conflicts as $conflict) {
                $day = $conflict->day_of_week;
                $startTime = $conflict->start_time->format('H:i');
                $endTime = $conflict->end_time->format('H:i');
                
                // Créer une clé unique pour le jour et l'heure
                $key = $day . '_' . $startTime;
                
                if (!$conflictsByDayAndTime->has($key)) {
                    $conflictsByDayAndTime->put($key, (object)[
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'conflicting_slots' => collect(),
                        'conflict_details' => $conflict->conflict_details ?? ''
                    ]);
                }
                
                // Ajouter les deux slots du conflit
                if ($conflict->slot1) {
                    $conflictsByDayAndTime[$key]->conflicting_slots->push((object)[
                        'slot_id' => $conflict->slot1->id,
                        'subject' => $conflict->slot1->subject->name ?? 'Inconnu',
                        'subject_code' => $conflict->slot1->subject->code ?? 'N/A',
                        'teacher' => $conflict->slot1->teacherProfile->full_name ?? 
                                     $conflict->slot1->teacher->name ?? 'Non assigné',
                        'classroom' => $conflict->slot1->classroom->code ?? null,
                        'color' => $conflict->slot1->color ?? '#EF4444'
                    ]);
                }
                
                if ($conflict->slot2) {
                    $conflictsByDayAndTime[$key]->conflicting_slots->push((object)[
                        'slot_id' => $conflict->slot2->id,
                        'subject' => $conflict->slot2->subject->name ?? 'Inconnu',
                        'subject_code' => $conflict->slot2->subject->code ?? 'N/A',
                        'teacher' => $conflict->slot2->teacherProfile->full_name ?? 
                                     $conflict->slot2->teacher->name ?? 'Non assigné',
                        'classroom' => $conflict->slot2->classroom->code ?? null,
                        'color' => $conflict->slot2->color ?? '#EF4444'
                    ]);
                }
            }
        }      
        
        // Calculer les statistiques BASÉES SUR LES TIMETABLESLOTS
        // Calculer les heures par matière depuis les slots
        $subjectHours = $timetable->timetableSlots
            ->whereNotNull('subject_id')
            ->groupBy('subject_id')
            ->map(function($slots) {
                $firstSlot = $slots->first();
                return (object)[
                    'subject_id' => $firstSlot->subject_id,
                    'name' => $firstSlot->subject->name ?? 'Inconnu',
                    'code' => $firstSlot->subject->code ?? 'N/A',
                    'color' => $firstSlot->subject->color ?? '#3B82F6',
                    'total_hours' => $slots->sum('duration'),
                    'slot_count' => $slots->count()
                ];
            })
            ->sortByDesc('total_hours')
            ->values();
        
        // Total heures depuis les slots
        $totalHours = $timetable->timetableSlots->sum('duration');
        $totalSlots = $timetable->timetableSlots->count();
        
        // Calculer les heures par professeur basées sur TimetableSlot
        $teacherHours = $timetable->timetableSlots
            ->filter(function($slot) {
                return $slot->teacher_id !== null;
            })
            ->groupBy('teacher_id')
            ->map(function($slots, $teacherId) {
                $firstSlot = $slots->first();
                $totalHours = $slots->sum('duration');
                
                return (object)[
                    'teacher_id' => $teacherId,
                    'teacher_name' => $firstSlot->teacher->name ?? 'Non assigné',
                    'teacher_profile' => $firstSlot->teacherProfile ?? null,
                    'total_hours' => $totalHours,
                    'slot_count' => $slots->count(),
                    'slots' => $slots
                ];
            })
            ->sortByDesc('total_hours')
            ->values();

        $startHour = 7; // 7h du matin
        $endHour = 18;  // 18h (6h du soir)
        
        return view('tenant.timetables.show', compact(
            'timetable', 
            'slotsByDay', 
            'unassignedAssignments',
            'conflicts',
            'conflictsByDayAndTime',
            'subjectHours',
            'totalHours',
            'totalSlots',
            'teacherHours',
            'startHour',   // <-- Ajouté
            'endHour'      // <-- Ajouté
        ));
    }  

    public function edit($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)->get();
        $academicYears = SchoolYear::where('is_active', true)->get();
        
        return view('tenant.timetables.edit', compact('timetable', 'classes', 'academicYears'));
    }

    public function update($tenat, Request $request, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:school_classes,id',
            'academic_year_id' => 'required|exists:school_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'type' => 'nullable|in:weekly,daily,custom',
        ]);

        $timetable->update($validated);

        return redirect()->route('timetables.show', [
            'tenant' => app('tenant')->name,
            'timetable' => $timetable->id
        ])->with('success', 'Emploi du temps mis à jour.');
    }

    public function destroy($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $timetable->delete();
        
        return redirect()->route('timetables.index', ['tenant' => app('tenant')->name])
            ->with('success', 'Emploi du temps archivé.');
    }

    public function destroySlot($tenant, $id)
    {
        try {
            // Récupérer le tenant actuel
            $tenant = app('tenant');
            
            // Trouver le slot avec vérification du tenant
            $slot = TimetableSlot::where('tenant_id', $tenant->id)
                                ->findOrFail($id);
            
            // Vérifier s'il y a des dépendances avant suppression
            // (ajoutez vos propres vérifications ici)
            
            $slot->delete();
            
            return back()->with('success', 'Créneau horaire supprimé avec succès');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Créneau horaire non trouvé' . $e->getMessage());
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }    

    public function manageSlots($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Récupérer les affectations disponibles pour cette classe
        $assignments = ClassAssignment::where('tenant_id', app('tenant')->id)
            ->where('class_id', $timetable->class_id)
            ->where('is_active', true)
            ->with(['subject', 'teacher', 'teacherProfile'])
            ->get();
            
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::where('status', 'active')->get();
        $classrooms = Classroom::where('is_active', true)->get();
        
        $timetable->load('timetableSlots.assignment');
        
        return view('tenant.timetables.manage-slots', compact(
            'timetable', 
            'assignments',
            'subjects', 
            'teachers', 
            'classrooms'
        ));
    }

    public function addSlot($tenat, Request $request, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'assignment_id' => 'nullable|exists:class_assignments,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'color' => 'nullable|string|max:7',
            'notes' => 'nullable|string',
        ]);

        // Si assignment_id est fourni, utiliser ses données
        if ($request->filled('assignment_id')) {
            $assignment = ClassAssignment::find($request->assignment_id);
            if ($assignment) {
                $validated['subject_id'] = $assignment->subject_id;
                $validated['teacher_id'] = $assignment->teacher_id;
            }
        }

        // Vérifier les conflits
        $conflict = $timetable->timetableSlots()
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        // if ($conflict) {
        //     return back()->with('error', 'Conflit d\'horaire détecté.');
        // }

        $validated['tenant_id'] = app('tenant')->id;
        $slot = $timetable->timetableSlots()->create($validated);

        return back()->with('success', 'Créneau ajouté avec succès.');
    }

    public function teacherTimetable($teacherId)
    {
        $teacher = Teacher::with('user')
            ->where('user_id', $teacherId)
            ->orWhere('id', $teacherId)
            ->firstOrFail();
            
        // Récupérer tous les emplois du temps où ce professeur est assigné
        $slots = TimetableSlot::where('tenant_id', app('tenant')->id)
            ->where('teacher_id', $teacher->user_id)
            ->with([
                'timetable.class', 
                'subject',
                'classroom'
            ])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_name');

        $weeklyHours = $slots->flatten()->sum('duration');

        return view('tenant.timetables.teacher-view', compact('teacher', 'slots', 'weeklyHours'));
    }

    public function classTimetable($tenat, SchoolClass $class)
    {
        // Vérifier que la classe appartient au tenant
        if ($class->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $timetable = Timetable::where('tenant_id', app('tenant')->id)
            ->with([
                'timetableSlots.subject',
                'timetableSlots.teacher',
                'timetableSlots.classroom',
                'timetableSlots.teacherProfile'
            ])
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$timetable) {
            return redirect()->route('timetables.create', ['tenant' => app('tenant')->name])
                ->with('class_id', $class->id)
                ->with('info', 'Aucun emploi du temps actif trouvé. Créez-en un nouveau pour cette classe.');
        }

        $slotsByDay = $timetable->timetableSlots->groupBy('day_of_week');

        return view('tenant.timetables.class-view', compact('class', 'timetable', 'slotsByDay'));
    }

    public function generateFromAssignments($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $slotsCreated = $timetable->generateFromAssignments();
        
        return redirect()->route('timetables.manage-slots', [
            'tenant' => app('tenant')->name,
            'timetable' => $timetable->id
        ])->with('success', "{$slotsCreated} créneaux générés à partir des affectations.");
    }

    public function checkConflicts($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $conflicts = $timetable->checkConflicts();
        
        return view('tenant.timetables.conflicts', compact('timetable', 'conflicts'));
    }

    public function print($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $timetable->load([
            'class',
            'timetableSlots.subject',
            'timetableSlots.teacher',
            'timetableSlots.classroom',
            'timetableSlots.teacherProfile'
        ]);
        
        $slotsByDay = $timetable->timetableSlots->groupBy('day_of_week');
        
        return view('tenant.timetables.print', compact('timetable', 'slotsByDay'));
    }

    public function duplicate($tenat, Timetable $timetable)
    {
        // Vérifier que le timetable appartient au tenant
        if ($timetable->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $newTimetable = $timetable->replicate();
        $newTimetable->name = $timetable->name . ' - Copie';
        $newTimetable->created_by = auth()->id();
        $newTimetable->save();

        // Dupliquer les créneaux
        foreach ($timetable->timetableSlots as $slot) {
            $newSlot = $slot->replicate();
            $newSlot->timetable_id = $newTimetable->id;
            $newSlot->save();
        }

        return redirect()->route('timetables.edit', [
            'tenant' => app('tenant')->name,
            'timetable' => $newTimetable->id
        ])->with('success', 'Emploi du temps dupliqué avec succès.');
    }
}