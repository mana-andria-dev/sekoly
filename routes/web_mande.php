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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::domain('site.test')->get('/', function () {
    return view('home');
});

Route::domain('site.test')->group(function () {
    Route::post('/register', [RegisterSchoolController::class, 'store'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::domain('{tenant}.site.test')->middleware(['tenant', 'auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

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
    
    // Subjects routes
    Route::prefix('subjects')->name('subjects.')->group(function() {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/create', [SubjectController::class, 'create'])->name('create');
        Route::post('/', [SubjectController::class, 'store'])->name('store');
        Route::get('/{subject}', [SubjectController::class, 'show'])->name('show');
        Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit');
        Route::put('/{subject}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
        
        // Additional routes
        Route::post('/{subject}/toggle-active', [SubjectController::class, 'toggleActive'])->name('toggle-active');
        Route::get('/api/search', [SubjectController::class, 'search'])->name('search');
    });

    Route::prefix('classes')->name('classes.')->group(function() {
        Route::get('/', [ClassController::class, 'index'])->name('index');
        Route::get('/create', [ClassController::class, 'create'])->name('create');
        Route::post('/', [ClassController::class, 'store'])->name('store');
        Route::get('/{schoolClass}', [ClassController::class, 'show'])->name('show');
        Route::get('/{schoolClass}/edit', [ClassController::class, 'edit'])->name('edit');
        Route::put('/{schoolClass}', [ClassController::class, 'update'])->name('update');
        Route::delete('/{schoolClass}', [ClassController::class, 'destroy'])->name('destroy');
    });


    /*
    Route::prefix('assignments')->name('assignments.')->group(function() {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
        Route::get('/{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit');
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');
        
        // Bulk operations
        Route::get('/class/{schoolClass}/bulk-create', [AssignmentController::class, 'bulkCreate'])->name('bulk-create');
        Route::post('/class/{schoolClass}/bulk-store', [AssignmentController::class, 'bulkStore'])->name('bulk-store');
        
        // Actions
        Route::post('/{assignment}/toggle-active', [AssignmentController::class, 'toggleActive'])->name('toggle-active');
        
        // API endpoints
        Route::get('/api/class/{schoolClass}', [AssignmentController::class, 'byClass'])->name('api.by-class');
        Route::get('/api/teacher/{teacher}', [AssignmentController::class, 'byTeacher'])->name('api.by-teacher');
    });
    */

    Route::post('/teachers/{teacher}/assignments', [TeacherController::class, 'addAssignment'])
        ->name('teachers.assignments.store');
    
    Route::delete('/teachers/{teacher}/assignments/{assignment}', [TeacherController::class, 'removeAssignment'])
        ->name('teachers.assignments.destroy');
    
    // Routes API pour les affectations
    Route::prefix('api')->group(function () {
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show']);
        Route::put('/assignments/{assignment}', [AssignmentController::class, 'update']);
        Route::get('/teachers/{teacher}/assignments', [AssignmentController::class, 'getAssignmentsByTeacher']);
        Route::get('/teachers/{teacher}/available-classes-subjects', [AssignmentController::class, 'getAvailableClassesAndSubjects']);
    });    

    // routes/tenant.php
    // routes/tenant.php
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::post('/', [TeacherController::class, 'store'])->name('store');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
        
        // Routes spécifiques
        Route::get('/{teacher}/schedule', [TeacherController::class, 'schedule'])->name('schedule');
        Route::get('/{teacher}/workload', [TeacherController::class, 'workloadReport'])->name('workload.report');
        
        // Contrats
        Route::get('/{teacher}/contracts', [TeacherContractController::class, 'index'])->name('contracts.index');
        Route::get('/{teacher}/contracts/create', [TeacherContractController::class, 'create'])->name('contracts.create');
        Route::post('/{teacher}/contracts', [TeacherContractController::class, 'store'])->name('contracts.store');
        Route::delete('/{teacher}/contracts/{contract}', [TeacherContractController::class, 'destroy'])->name('contracts.destroy');
        
        // Évaluations
        Route::get('/{teacher}/evaluations', [TeacherEvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/{teacher}/evaluations/create', [TeacherEvaluationController::class, 'create'])->name('evaluations.create');
        Route::post('/{teacher}/evaluations', [TeacherEvaluationController::class, 'store'])->name('evaluations.store');
        
        // Disponibilités
        Route::get('/{teacher}/availabilities', [TeacherAvailabilityController::class, 'index'])->name('availabilities.index');
        Route::post('/{teacher}/availabilities', [TeacherAvailabilityController::class, 'store'])->name('availabilities.store');
        Route::delete('/{teacher}/availabilities/{availability}', [TeacherAvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    }); 


    // Routes emploi du temps
    Route::resource('timetables', TimetableController::class);

    Route::get('/timetables/{timetable}/manage-slots', [TimetableController::class, 'manageSlots'])
        ->name('timetables.manage-slots');

    Route::post('/timetables/{timetable}/slots', [TimetableController::class, 'addSlot'])
        ->name('timetables.add-slot');

    // AJOUTEZ CETTE ROUTE POUR SUPPRIMER LES CRÉNEAUX
    Route::delete('/timetable-slots/{timetable_slot}', [TimetableController::class, 'destroySlot'])
        ->name('timetable-slots.destroy');

    Route::post('/timetables/{timetable}/generate-from-assignments', [TimetableController::class, 'generateFromAssignments'])
        ->name('timetables.generate-from-assignments');

    Route::get('/timetables/{timetable}/conflicts', [TimetableController::class, 'checkConflicts'])
        ->name('timetables.conflicts');

    Route::get('/timetables/{timetable}/print', [TimetableController::class, 'print'])
        ->name('timetables.print');

    Route::post('/timetables/{timetable}/duplicate', [TimetableController::class, 'duplicate'])
        ->name('timetables.duplicate');

    // Vue par professeur
    Route::get('/teachers/{teacher}/timetable', [TimetableController::class, 'teacherTimetable'])
        ->name('teachers.timetable');

    // Vue par classe
    Route::get('/classes/{class}/timetable', [TimetableController::class, 'classTimetable'])
        ->name('classes.timetable');

    // Leçons
    Route::resource('lessons', LessonController::class);
    Route::patch('lessons/{lesson}/status', [LessonController::class, 'updateStatus'])->name('lessons.status');
    
    // Devoirs
    Route::resource('homeworks', HomeworkController::class);
    Route::post('homeworks/{homework}/submit', [HomeworkController::class, 'submit'])->name('homeworks.submit');
    Route::post('homeworks/submissions/{submission}/grade', [HomeworkController::class, 'grade'])->name('homeworks.grade');
    
    // Examens
    Route::resource('exams', ExamController::class);
    Route::post('exams/{exam}/results', [ExamController::class, 'storeResults'])->name('exams.results.store');
    Route::get('exams/{exam}/results', [ExamController::class, 'results'])->name('exams.results');
    
    //Note
    Route::resource('grades', GradeController::class);
    Route::get('grades/bulk/{classId}/{subjectId}', [GradeController::class, 'bulkCreate'])->name('grades.bulk.create');
    Route::post('grades/bulk', [GradeController::class, 'bulkStore'])->name('grades.bulk.store');

    Route::get('classes/{classId}/students', function($classId) {
        $class = App\Models\SchoolClass::findOrFail($classId);
        return $class->students()->where('role', 'student')->get(['id', 'first_name', 'last_name']);
    });    
    
    // Bulletins
    Route::resource('report-cards', ReportCardController::class);
    Route::post('report-cards/generate', [ReportCardController::class, 'generate'])->name('report-cards.generate');
    Route::post('report-cards/{reportCard}/publish', [ReportCardController::class, 'publish'])->name('report-cards.publish');
    Route::get('report-cards/{reportCard}/print', [ReportCardController::class, 'print'])->name('report-cards.print');
    Route::get('classes/{class}/report-cards/{period?}', [ReportCardController::class, 'classReportCards'])->name('report-cards.class');

});

