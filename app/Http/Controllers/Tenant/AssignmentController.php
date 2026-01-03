<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClassAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function index($tenant, User $teacher = null)
    {
        $query = ClassAssignment::query()
            ->with(['subject', 'schoolClass', 'teacher'])
            ->forTenant()
            ->current()
            ->active();

        if ($teacher) {
            $query->forTeacher($teacher->id);
        }

        $assignments = $query->orderBy('start_date', 'desc')->paginate(10);

        $classes = SchoolClass::forTenant()->active()->get();
        $subjects = Subject::forTenant()->get();
        $teachers = User::forTenant()
            ->where('role', 'teacher')
            ->where('status', 'active')
            ->get();

        return view('tenant.assignments.index', compact('assignments', 'classes', 'subjects', 'teachers', 'teacher'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate(ClassAssignment::getValidationRules());

            $data['tenant_id'] = app('tenant')->id;
            
            // Vérifier si l'affectation existe déjà
            $existing = ClassAssignment::where([
                'tenant_id' => $data['tenant_id'],
                'class_id' => $data['class_id'],
                'subject_id' => $data['subject_id'],
                'teacher_id' => $data['teacher_id']
            ])->active()->current()->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'teacher_id' => 'Ce professeur est déjà affecté à cette matière dans cette classe.'
                ]);
            }

            $assignment = ClassAssignment::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Affectation créée avec succès.',
                'assignment' => $assignment->load(['subject', 'schoolClass', 'teacher'])
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.'
            ], 500);
        }
    }

    public function update(Request $request, $tenant, ClassAssignment $assignment)
    {
        try {
            $data = $request->validate(ClassAssignment::getValidationRules($assignment->id));

            // Vérifier les conflits (exclure l'affectation actuelle)
            $existing = ClassAssignment::where([
                'tenant_id' => app('tenant')->id,
                'class_id' => $data['class_id'],
                'subject_id' => $data['subject_id'],
                'teacher_id' => $data['teacher_id']
            ])->where('id', '!=', $assignment->id)
              ->active()
              ->current()
              ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'teacher_id' => 'Ce professeur est déjà affecté à cette matière dans cette classe.'
                ]);
            }

            $assignment->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Affectation mise à jour avec succès.',
                'assignment' => $assignment->fresh(['subject', 'schoolClass', 'teacher'])
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.'
            ], 500);
        }
    }

    public function destroy($tenant, ClassAssignment $assignment)
    {
        try {
            $assignment->deactivate();

            return response()->json([
                'success' => true,
                'message' => 'Affectation supprimée avec succès.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression.'
            ], 500);
        }
    }

    public function getAssignmentsByTeacher($tenant, User $teacher)
    {
        $assignments = ClassAssignment::with(['subject', 'schoolClass'])
            ->forTenant()
            ->forTeacher($teacher->id)
            ->active()
            ->current()
            ->orderBy('start_date', 'desc')
            ->get();

        $totalHours = $assignments->sum('hours_per_week');

        return response()->json([
            'assignments' => $assignments,
            'total_hours' => $totalHours,
            'count' => $assignments->count()
        ]);
    }

    public function getAvailableClassesAndSubjects($tenant, User $teacher)
    {
        // Classes où le professeur n'est pas encore affecté
        $assignedClassIds = ClassAssignment::forTenant()
            ->forTeacher($teacher->id)
            ->active()
            ->current()
            ->pluck('class_id');

        $availableClasses = SchoolClass::forTenant()
            ->active()
            ->whereNotIn('id', $assignedClassIds)
            ->get();

        // Matières que le professeur peut enseigner (basé sur sa spécialisation)
        $availableSubjects = Subject::forTenant()
            ->where(function($query) use ($teacher) {
                // Vous pouvez ajuster cette logique selon vos besoins
                $query->where('name', 'like', "%{$teacher->specialization}%")
                      ->orWhere('category', $teacher->specialization);
            })
            ->get();

        return response()->json([
            'classes' => $availableClasses,
            'subjects' => $availableSubjects
        ]);
    }
}