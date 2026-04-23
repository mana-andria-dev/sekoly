<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\ClassAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class TeacherAssignmentController extends Controller
{

    public function addAssignment(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        // Valider les données
        $validatedData = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            // 'hours_per_week' => 'required|integer|min:1|max:40',
            // 'coefficient' => 'required|numeric|min:0.1|max:10',
            // 'day_of_week' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            // 'start_date' => 'required|date',
            // 'end_date' => 'nullable|date|after:start_date',
        ]);

        // VÉRIFICATION CRITIQUE : S'assurer que le professeur a un user_id
        if (!$teacher->user_id) {
            return back()->with('error', 'Ce professeur n\'a pas de compte utilisateur. Veuillez d\'abord créer un compte utilisateur pour ce professeur.');
        }

        // Vérifier si l'utilisateur existe
        $user = User::where('id', $teacher->user_id)
            ->first();

        if (!$user) {
            return back()->with('error', 'Le compte utilisateur associé à ce professeur n\'existe pas.');
        }

        // Vérifier le rôle
        if ($user->role !== 'teacher') {
            \Log::warning("L'utilisateur {$user->id} associé au professeur {$teacher->id} n'a pas le rôle 'teacher' (rôle: {$user->role})");
            // Optionnel : corriger automatiquement
            $user->role = 'teacher';
            $user->save();
        }

        // Vérifier si l'affectation existe déjà
        $existing = ClassAssignment::where([
            'class_id' => $validatedData['class_id'],
            'subject_id' => $validatedData['subject_id'],
            'teacher_id' => $teacher->user_id // Utiliser user_id
        ])->active()->current()->first();

        if ($existing) {
            return back()->with('error', 'Ce professeur est déjà affecté à cette matière dans cette classe.');
        }

        try {
            // Créer l'affectation
            $assignment = new ClassAssignment();
            $assignment->class_id = $validatedData['class_id'];
            $assignment->subject_id = $validatedData['subject_id'];
            $assignment->teacher_id = $teacher->user_id; // C'est le user_id
            $assignment->hours_per_week = 1;//$validatedData['hours_per_week'];
            $assignment->coefficient = 1;//$validatedData['coefficient'];
            $assignment->day_of_week = 1;//$validatedData['day_of_week'] ?? null;
            $assignment->start_date = null;//$validatedData['start_date'];
            $assignment->end_date = null;//$validatedData['end_date'] ?? null;
            $assignment->status = 'active';
            $assignment->is_active = true;
            
            $assignment->save();

            return back()->with('success', 'Affectation ajoutée avec succès.');

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Erreur création affectation', [
                'error' => $e->getMessage(),
                'teacher_id' => $teacher->id,
                'user_id' => $teacher->user_id,
                'data' => $validatedData
            ]);
            
            return back()->with('error', 'Erreur technique: ' . $e->getMessage());
        }
    }

    public function removeAssignment($id)
    {
        // if ($assignment->teacher_id !== $teacher->id) {
        //     return back()->with('error', 'Cette affectation ne correspond pas au professeur.');
        // }
        $assignment = ClassAssignment::findOrFail($id);
        $assignment->deactivate();

        return back()->with('success', 'Affectation supprimée avec succès.');
    } 

}

