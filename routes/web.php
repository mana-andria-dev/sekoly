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
use App\Http\Controllers\Tenant\FeeStructureController;
use App\Http\Controllers\Tenant\FeePaymentController;
use App\Http\Controllers\Tenant\SubscriptionController;
use App\Http\Controllers\Tenant\StudentDocumentController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Middleware\TenantAuthMiddleware;
use App\Http\Controllers\Admin\SchoolController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/debug-connection', function() {
    $tenant = tenant();
    
    return [
        'tenant_exists' => $tenant ? true : false,
        'tenant_id' => $tenant ? $tenant->id : null,
        'database_name' => DB::connection()->getDatabaseName(),
        'default_connection' => config('database.default'),
        'users_table_exists' => Schema::hasTable('users'),
        'users_count' => DB::table('users')->count(),
        'users' => DB::table('users')->select('id', 'email', 'role')->get()
    ];
});

// Routes pour l'administration centrale
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.submit');
    });
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('schools', App\Http\Controllers\Admin\SchoolController::class);
        
        // Actions supplémentaires pour les écoles
        Route::post('/schools/{id}/activate', [SchoolController::class, 'activate'])->name('schools.activate');
        Route::post('/schools/{id}/deactivate', [SchoolController::class, 'deactivate'])->name('schools.deactivate');
        Route::post('/schools/{id}/resend-access', [SchoolController::class, 'resendAccess'])->name('schools.resend-access');

        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('index');
            Route::get('/create/{tenantId}', [App\Http\Controllers\Admin\SubscriptionController::class, 'create'])->name('create');
            Route::post('/store/{tenantId}', [App\Http\Controllers\Admin\SubscriptionController::class, 'store'])->name('store');
            Route::post('/cancel/{id}', [App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('cancel');
        });
        
        Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
    });
});

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
    'tenant', // Le middleware de stancl/tenancy
    'tenant.db',
    'ensure.tenant.connection' 
])->group(function () {

    // Routes d'authentification (doivent être DANS le groupe tenant)
    Route::get('/login', [App\Http\Controllers\Tenant\Auth\LoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Tenant\Auth\LoginController::class, 'login'])->name('tenant.login.submit');
    Route::post('/logout', [App\Http\Controllers\Tenant\Auth\LoginController::class, 'logout'])->name('tenant.logout');    

    // Routes d'abonnement (hors middleware subscription)
    Route::get('/subscription/expired', [SubscriptionController::class, 'expired'])->name('tenant.subscription.expired');
    Route::get('/subscription/info', [SubscriptionController::class, 'info'])->name('tenant.subscription.info');
    Route::get('/subscription/status', [SubscriptionController::class, 'checkStatus'])->name('tenant.subscription.status');

    // Routes protégées
    Route::middleware([
        \App\Http\Middleware\TenantAuthMiddleware::class,
        'tenant.subscription'
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

        // Gestion des frais de scolarité
        Route::prefix('fees')->name('fees.')->group(function () {
            // Structures de frais
            Route::resource('structures', FeeStructureController::class);
            Route::post('structures/generate-for-class', [FeeStructureController::class, 'generateForClass'])->name('structures.generate');
            
            // Paiements
            Route::resource('payments', FeePaymentController::class);
            Route::get('student-balance/{studentId}', [FeePaymentController::class, 'studentBalance'])->name('student.balance');
        });        
        
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

        Route::prefix('students/{student}/documents')->name('students.documents.')->group(function () {
            Route::get('generate', [StudentDocumentController::class, 'generate'])->name('generate');
            Route::post('generate', [StudentDocumentController::class, 'generateAndDownload'])->name('store');
        });
        
        // Routes pour les documents
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [StudentDocumentController::class, 'index'])->name('index');
            Route::get('{document}/download', [StudentDocumentController::class, 'download'])->name('download');
            Route::get('{document}/preview', [StudentDocumentController::class, 'preview'])->name('preview');
            Route::delete('{document}', [StudentDocumentController::class, 'destroy'])->name('destroy');
            Route::patch('{document}/status', [StudentDocumentController::class, 'updateStatus'])->name('update-status');
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

        // Vue par classe
        Route::get('/classes/{class}/timetable', [TimetableController::class, 'classTimetable'])
            ->name('classes.timetable');            

        Route::resource('lessons', LessonController::class);
        Route::patch('lessons/{lesson}/status', [LessonController::class, 'updateStatus'])->name('lessons.status');

        Route::resource('homeworks', HomeworkController::class);
        Route::post('homeworks/{homework}/submit', [HomeworkController::class, 'submit'])->name('homeworks.submit');
        Route::post('homeworks/submissions/{submission}/grade', [HomeworkController::class, 'grade'])->name('homeworks.grade');        
        Route::resource('exams', ExamController::class);
        Route::post('exams/{exam}/results', [ExamController::class, 'storeResults'])->name('exams.results.store');
        Route::get('exams/{exam}/results', [ExamController::class, 'results'])->name('exams.results');

        Route::resource('grades', GradeController::class);
        
        Route::resource('report-cards', ReportCardController::class);
        Route::post('report-cards/generate', [ReportCardController::class, 'generate'])->name('report-cards.generate');
        Route::post('report-cards/{reportCard}/publish', [ReportCardController::class, 'publish'])->name('report-cards.publish');
        Route::get('report-cards/{reportCard}/print', [ReportCardController::class, 'print'])->name('report-cards.print');
        Route::get('classes/{class}/report-cards/{period?}', [ReportCardController::class, 'classReportCards'])->name('report-cards.class');        
        
        // Tes routes API et spécifiques...
        Route::get('/classes/{classId}/students', function($classId) {
            $class = App\Models\SchoolClass::findOrFail($classId);
            return $class->students()->where('role', 'student')->get(['id', 'first_name', 'last_name']);
        });

    });

});