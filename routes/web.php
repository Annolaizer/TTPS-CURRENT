<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\TrainingAssignmentController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Organization\DashboardController as OrganizationDashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\SettingsController as TeacherSettingsController;
use App\Http\Controllers\Teacher\TrainingController as TeacherTrainingController;
use App\Http\Controllers\CpdFacilitator\DashboardController as CpdFacilitatorDashboardController;
use App\Http\Controllers\CpdFacilitator\SettingsController as CpdFacilitatorSettingsController;
use App\Http\Controllers\Organization\TrainingController as OrganizationTrainingController;
use App\Http\Controllers\QualifiedTeacherController;
use App\Http\Controllers\QualifiedFacilitatorsController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\CpdFacilitator\CPDFacilitatorTrainingController;
use App\Http\Controllers\OrganizationTrainingAssignmentController;

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/frequently-asked-questions', function () {
    return view('Faqs.index');
})->name('faqs');

Route::get('/news', function () {
    return view('news.index');
})->name('news');

Route::get('/about', function () {
    return view('about.index');
})->name('about');

// Location API Routes
Route::get('/api/regions', [LocationController::class, 'getRegions'])->name('api.regions');
Route::get('/api/districts/{region}', [LocationController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/wards/{district}', [LocationController::class, 'getWards'])->name('api.wards');

// ASSIGN TRAINING ROUTE
Route::get('/qualified-teachers/{training_code}', [QualifiedTeacherController::class, 'index'])->name('qualified-teachers');
Route::get('/qualified-facilitators/{training_code}', [QualifiedFacilitatorsController::class, 'index'])->name('qualified-facilitators');

// Training routes
Route::prefix('trainings')->name('trainings.')->group(function () {
    // Training Assignment routes
    Route::get('/{trainingCode}/assignment', [TrainingAssignmentController::class, 'show'])->name('assignment.show');
    Route::get('/{trainingCode}/participants/data', [TrainingAssignmentController::class, 'getParticipants'])->name('participants.data');
    Route::get('/{trainingCode}/participants/{participantId}/report', [TrainingAssignmentController::class, 'downloadReport'])->name('participants.report');
    Route::post('/{trainingCode}/update-phase', [TrainingAssignmentController::class, 'updatePhase'])->name('phase.update');
    
    Route::get('/{trainingCode}/available-teachers', [TrainingAssignmentController::class, 'getAvailableTeachers'])->name('teachers.available');
    Route::get('/{trainingCode}/available-facilitators', [TrainingAssignmentController::class, 'getAvailableFacilitators'])->name('facilitators.available');
    
    Route::post('/{trainingCode}/assign-teachers', [TrainingAssignmentController::class, 'assignTeachers'])->name('teachers.assign');
    Route::post('/{trainingCode}/assign-facilitators', [TrainingAssignmentController::class, 'assignFacilitators'])->name('facilitators.assign');
    Route::post('/{trainingCode}/assign-training-participants', [TrainingAssignmentController::class, 'assignParticipants'])->name('participants.assign');
    
    Route::delete('/{trainingCode}/remove-teacher/{teacherId}', [TrainingAssignmentController::class, 'removeTeacher'])->name('teachers.remove');
    Route::delete('/{trainingCode}/remove-facilitator/{facilitatorId}', [TrainingAssignmentController::class, 'removeFacilitator'])->name('facilitators.remove');
});

    // ####### LOGIN ROUTES #############
    Route::get('/login', function () {
        return view('login.index');
    })->name('login');

    Route::get('/login/{role}', function ($role) {
        $roles = ['teacher', 'organization', 'cpd_facilitator', 'admin', 'super_administrator'];

        if (!in_array($role, $roles)) {
            abort(404);
        }

        $displayRole = ucfirst(str_replace('_', ' ', $role));
        
        if ($role === 'admin' || $role === 'super_administrator') {
            return view('auth.login.admin.index');
        }

        return view('auth.login.index', ['role' => $displayRole]);
    })->name('login.role');

    Route::post('/login/authenticate', [LoginController::class, 'authenticate'])
        ->name('login.authenticate');

    // ########## END LOGIN ROUTES ##############

    // ########### REGISTRATION ROUTES ###########
    Route::get('/register', function () {
        return view('register.index');
    })->name('register');

    Route::get('/register/{role}', function ($role) {
        $roles = ['teacher', 'organization', 'cpd_facilitator'];

        if (!in_array($role, $roles)) {
            abort(404);
        }

        if ($role === 'organization') {
            return view('auth.register.organization.index');
        }

        return view('auth.register.index', ['role' =>  $role]);
    })->name('register.role');

    Route::post('/register/store', [App\Http\Controllers\Auth\RegisterController::class, 'store'])
        ->name('register.store');

    Route::post('/register/organization/store', [App\Http\Controllers\Auth\RegisterController::class, 'storeOrganization'])
        ->name('register.organization.store');

        // ####### END REGISTRATION ROUTES ##############

// Authentication Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Teacher routes
    Route::prefix('teacher')
        ->middleware(['auth', \App\Http\Middleware\TeacherMiddleware::class])
        ->name('teacher.')
        ->group(function () {
            Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [TeacherDashboardController::class, 'profile'])->name('profile');
            Route::get('/profile/setup', [TeacherDashboardController::class, 'profileSetup'])->name('profile.setup');
            Route::get('/training', [TeacherTrainingController::class, 'index'])->name('training');
            Route::get('/training/{id}', [TeacherTrainingController::class, 'show'])->name('training.show');
            Route::get('/settings', [TeacherSettingsController::class, 'index'])->name('settings');
            Route::get('/basic-info', function() {
                return view('teacher.basic_info.index');
            })->name('basic_info');
        });

    // CPD Facilitator routes
    Route::prefix('cpd_facilitator')
        ->middleware(['auth', \App\Http\Middleware\CpdFacilitatorMiddleware::class])
        ->name('cpd_facilitator.')
        ->group(function () {
            Route::get('/dashboard', [CpdFacilitatorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [CpdFacilitatorDashboardController::class, 'profile'])->name('profile');
            Route::get('/profile/setup', [CpdFacilitatorDashboardController::class, 'profileSetup'])->name('profile.setup');
            Route::get('/training', [CPDFacilitatorTrainingController::class, 'index'])->name('training');
            Route::get('/settings', [CpdFacilitatorSettingsController::class, 'index'])->name('settings');
            Route::get('/basic-info', function() {
                return view('cpd_facilitator.basic_info.index');
            })->name('cpd_basic_info');
        });

    // Organization routes
    Route::middleware(['auth', \App\Http\Middleware\OrganizationMiddleware::class])->prefix('organization')->name('organization.')->group(function () {
        // Dashboard routes
        Route::get('/dashboard', [OrganizationDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [OrganizationDashboardController::class, 'profile'])->name('profile');
        Route::get('/profile/setup', [OrganizationDashboardController::class, 'profileSetup'])->name('profile.setup');
        Route::put('/profile/update', [OrganizationDashboardController::class, 'profileUpdate'])->name('profile.update');
        
        // Training routes
        Route::get('/trainings', [\App\Http\Controllers\Organization\TrainingController::class, 'index'])->name('trainings');
        Route::post('/trainings', [\App\Http\Controllers\Organization\TrainingController::class, 'store'])->name('trainings.store');
        Route::get('/trainings/{training}', [\App\Http\Controllers\Organization\TrainingController::class, 'show'])->name('trainings.show');
        Route::get('/trainings/{training}/report', [\App\Http\Controllers\Organization\TrainingController::class, 'generateReport'])->name('trainings.report');
        Route::get('/{trainingCode}/assignment', [OrganizationTrainingAssignmentController::class, 'index'])->name('assignment.show');
    });
});

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/data', [UserManagementController::class, 'getUsers'])->name('data');
        Route::get('/{userId}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::put('/{userId}', [UserManagementController::class, 'update'])->name('update');
        Route::post('/{userId}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{userId}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

    // Institution routes
    Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
    Route::get('/institutions/create', [InstitutionController::class, 'create'])->name('institutions.create');
    Route::post('/institutions', [InstitutionController::class, 'store'])->name('institutions.store');
    Route::get('/institutions/{id}', [InstitutionController::class, 'show'])->name('institutions.show');
    Route::match(['post', 'put'], '/institutions/{id}', [InstitutionController::class, 'update'])->name('institutions.update');
    Route::post('/institutions/{id}/toggle-status', [InstitutionController::class, 'toggleStatus'])->name('institutions.toggle-status');
    Route::delete('/institutions/{id}/approval-letter', [InstitutionController::class, 'deleteApprovalLetter'])->name('institutions.delete-approval-letter');
    Route::get('/storage/{path}', function($path) {
        return response()->file(storage_path('app/public/' . $path));
    })->where('path', '.*');

    // Teachers routes
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [AdminTeacherController::class, 'index'])->name('index');
        Route::get('/{user_id}', [AdminTeacherController::class, 'show'])->name('show');
        Route::get('/{user_id}/edit', [AdminTeacherController::class, 'edit'])->name('edit');
        Route::put('/{user_id}', [AdminTeacherController::class, 'update'])->name('update');
        Route::post('/{user_id}/toggle-status', [AdminTeacherController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/verify-completed', [AdminTeacherController::class, 'verifyCompleted'])->name('verify-completed');
    });

    // Training routes
    Route::prefix('trainings')->name('trainings.')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('index');
        Route::get('/data', [TrainingController::class, 'getTrainings'])->name('data');
        Route::post('/', [TrainingController::class, 'store'])->name('store');
        
        // Training verification routes
        Route::put('/{trainingCode}/verify', [TrainingController::class, 'verify'])->name('verify');
        Route::put('/{trainingCode}/reject', [TrainingController::class, 'reject'])->name('reject');
        
        Route::get('/{trainingCode}', [TrainingController::class, 'show'])->name('show');
        Route::put('/{trainingCode}/update', [TrainingController::class, 'update'])->name('update');
        Route::delete('/{trainingCode}', [TrainingController::class, 'destroy'])->name('destroy');
        
        // Training Assignment routes
        Route::get('/{trainingCode}/assignment', [TrainingAssignmentController::class, 'show'])->name('assignment');
        Route::get('/{trainingCode}/participants/data', [TrainingAssignmentController::class, 'getParticipants'])->name('participants.data');
        Route::get('/{trainingCode}/participants/{participantId}/report', [TrainingAssignmentController::class, 'downloadReport'])->name('participants.report');
        
        Route::get('/{trainingCode}/available-teachers', [TrainingAssignmentController::class, 'getAvailableTeachers'])->name('teachers.available');
        Route::get('/{trainingCode}/available-facilitators', [TrainingAssignmentController::class, 'getAvailableFacilitators'])->name('facilitators.available');
        
        Route::post('/{trainingCode}/assign-teachers', [TrainingAssignmentController::class, 'assignTeachers'])->name('teachers.assign');
        Route::post('/{trainingCode}/assign-facilitators', [TrainingAssignmentController::class, 'assignFacilitators'])->name('facilitators.assign');
        
        Route::delete('/{trainingCode}/remove-teacher/{teacherId}', [TrainingAssignmentController::class, 'removeTeacher'])->name('teachers.remove');
        Route::delete('/{trainingCode}/remove-facilitator/{facilitatorId}', [TrainingAssignmentController::class, 'removeFacilitator'])->name('facilitators.remove');
        Route::get('/{training}/report', [TrainingController::class, 'generateReport'])->name('report');
    });

    // Reports routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export-pdf', [ReportController::class, 'generatePDF'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });
});

// Debug route to catch unmatched routes
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Route not found',
        'requested_url' => request()->url(),
        'method' => request()->method()
    ], 404);
});