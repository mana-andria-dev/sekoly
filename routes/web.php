<?php

require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterSchoolController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\SchoolYearController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\StudentController;
use App\Http\Controllers\Tenant\SubjectController;
use App\Http\Controllers\Tenant\ClassController;
use App\Http\Controllers\Tenant\AssignmentController;
use App\Http\Controllers\Tenant\TeacherController;
use App\Http\Controllers\Tenant\TeacherAssignmentController;
use App\Http\Controllers\Tenant\TeacherContractController;
use App\Http\Controllers\Tenant\TeacherEvaluationController;
use App\Http\Controllers\Tenant\TeacherAvailabilityController;
use App\Http\Controllers\Tenant\TimetableController;
use App\Http\Controllers\Tenant\LessonController;
use App\Http\Controllers\Tenant\HomeworkController;
use App\Http\Controllers\Tenant\ExamController;
use App\Http\Controllers\Tenant\GradeController;
use App\Http\Controllers\Tenant\ReportCardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes du site central (landing page, registration)
Route::domain('site.test')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');
    
    Route::post('/inscription', [RegisterSchoolController::class, 'store'])->name('inscription');
});

// Routes centrales avec auth (profil, etc.)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-subjects-list', function() {
    $subjects = \App\Models\Subject::all(['id', 'name']);
    return response()->json($subjects);
});

// Routes TENANT (écoles) - stancl/tenancy gère automatiquement l'initialisation
Route::middleware([
    'web',
    'auth',  // L'utilisateur doit être connecté sur le domaine central
    'tenant', // Le middleware de stancl/tenancy
    'tenant.db'
])->group(function () {

    Route::get('/test-subject/{id}', function($id) {
        $tenant = tenant();
        return response()->json([
            'message' => 'Route test fonctionne',
            'id' => $id,
            'tenant_name' => $tenant ? $tenant->name : 'No tenant',
            'tenant_id' => $tenant ? $tenant->id : 'No tenant',
            'database' => DB::connection()->getDatabaseName()
        ]);
    })->name('test.subject');    
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
    
    // Toutes tes ressources tenant...
    Route::resource('users', UserController::class);
    Route::resource('school-years', SchoolYearController::class);
    Route::patch('school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');
    
    Route::prefix('students')->group(function () {
        Route::get('/import', [StudentController::class, 'importForm'])->name('students.import.form');
        Route::post('/import', [StudentController::class, 'import'])->name('students.import');
        Route::get('/import/template', [StudentController::class, 'downloadTemplate'])->name('students.import.template');
        Route::get('/import/report/{reportId}', [StudentController::class, 'downloadReport'])->name('students.import.report');
        Route::get('/export', [StudentController::class, 'export'])->name('students.export');
        Route::post('/{student}/enrollment', [StudentController::class, 'updateEnrollment'])->name('students.enrollment.update');
        Route::post('/{student}/enrollment/new', [StudentController::class, 'createEnrollment'])->name('students.enrollment.create');
        Route::patch('/{student}/activate', [StudentController::class, 'activate'])->name('students.activate');
        Route::patch('/{student}/deactivate', [StudentController::class, 'deactivate'])->name('students.deactivate');
    });
    
    Route::resource('students', StudentController::class);
    
    // Continue avec toutes tes autres routes...
    // Subjects, classes, teachers, timetables, lessons, homeworks, exams, grades, report-cards
    
    Route::resource('subjects', SubjectController::class);
    Route::patch('subjects/{subject}/toggle-active', [SubjectController::class, 'toggleActive'])
        ->name('subjects.toggle-active');

    Route::resource('classes', ClassController::class);
    Route::resource('teachers', TeacherController::class);

    Route::post('/teachers/{teacher}/assignments', [TeacherAssignmentController::class, 'addAssignment'])
        ->name('teachers.assignments.store');
    
    Route::delete('/teachers/{teacher}/assignments/{assignment}', [TeacherAssignmentController::class, 'removeAssignment'])
        ->name('teachers.assignments.destroy'); 

    // Contrats
    Route::get('/{teacher}/contracts', [TeacherContractController::class, 'index'])->name('teachers.contracts.index');
    Route::get('/{teacher}/contracts/create', [TeacherContractController::class, 'create'])->name('teachers.contracts.create');
    Route::post('/{teacher}/contracts', [TeacherContractController::class, 'store'])->name('teachers.contracts.store');
    Route::delete('/{teacher}/contracts/{contract}', [TeacherContractController::class, 'destroy'])->name('teachers.contracts.destroy');

    // Évaluations
    Route::get('/{teacher}/evaluations', [TeacherEvaluationController::class, 'index'])->name('teachers.evaluations.index');
    Route::get('/{teacher}/evaluations/create', [TeacherEvaluationController::class, 'create'])->name('teachers.evaluations.create');
    Route::post('/{teacher}/evaluations', [TeacherEvaluationController::class, 'store'])->name('teachers.evaluations.store');    

    // Disponibilités
    Route::get('/{teacher}/availabilities', [TeacherAvailabilityController::class, 'index'])->name('teachers.availabilities.index');
    Route::post('/{teacher}/availabilities', [TeacherAvailabilityController::class, 'store'])->name('teachers.availabilities.store');
    Route::delete('/{teacher}/availabilities/{availability}', [TeacherAvailabilityController::class, 'destroy'])->name('teachers.availabilities.destroy');    



    Route::resource('timetables', TimetableController::class);
    Route::resource('lessons', LessonController::class);
    Route::resource('homeworks', HomeworkController::class);
    Route::resource('exams', ExamController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('report-cards', ReportCardController::class);
    
    // Tes routes API et spécifiques...
    Route::get('/classes/{classId}/students', function($classId) {
        $class = App\Models\SchoolClass::findOrFail($classId);
        return $class->students()->where('role', 'student')->get(['id', 'first_name', 'last_name']);
    });
});

// Note : Supprime les routes avec {tenant}.site.test car stancl/tenancy 
// utilise le resolver de domaine automatiquement