<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\TrainingAssignmentController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\CpdFacilitator\DashboardController as CpdFacilitatorDashboardController;
use App\Http\Controllers\Organization\DashboardController as OrganizationDashboardController;
use App\Http\Controllers\Organization\TrainingController as OrganizationTrainingController;
use App\Http\Controllers\Admin\InstitutionController;

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

// Test route for admin dashboard without auth
Route::get('/test/admin/dashboard', [AdminDashboardController::class, 'index'])->name('test.admin.dashboard');
Route::get('/test/admin/dashboard/data', [AdminDashboardController::class, 'apiData'])->name('test.admin.dashboard.data');

// Location API Routes
Route::get('/api/districts/{region}', [LocationController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/wards/{district}', [LocationController::class, 'getWards'])->name('api.wards');

// Training routes
Route::prefix('trainings')->middleware(['auth'])->name('trainings.')->group(function () {
    // Training Assignment routes
    Route::get('/{trainingCode}/assignment', [TrainingAssignmentController::class, 'show'])->name('assignment.show');
    Route::get('/{trainingCode}/participants/data', [TrainingAssignmentController::class, 'getParticipants'])->name('participants.data');
    Route::get('/{trainingCode}/participants/{participantId}/report', [TrainingAssignmentController::class, 'downloadReport'])->name('participants.report');
    Route::post('/{trainingCode}/update-phase', [TrainingAssignmentController::class, 'updatePhase'])->name('phase.update');
    
    Route::get('/{trainingCode}/available-teachers', [TrainingAssignmentController::class, 'getAvailableTeachers'])->name('teachers.available');
    Route::get('/{trainingCode}/available-facilitators', [TrainingAssignmentController::class, 'getAvailableFacilitators'])->name('facilitators.available');
    
    Route::post('/{trainingCode}/assign-teachers', [TrainingAssignmentController::class, 'assignTeachers'])->name('teachers.assign');
    Route::post('/{trainingCode}/assign-facilitators', [TrainingAssignmentController::class, 'assignFacilitators'])->name('facilitators.assign');
    
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
        });

    // CPD Facilitator routes
    Route::prefix('cpd_facilitator')
        ->middleware(['auth', \App\Http\Middleware\CpdFacilitatorMiddleware::class])
        ->name('cpd_facilitator.')
        ->group(function () {
            Route::get('/dashboard', [CpdFacilitatorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [CpdFacilitatorDashboardController::class, 'profile'])->name('profile');
            Route::get('/profile/setup', [CpdFacilitatorDashboardController::class, 'profileSetup'])->name('profile.setup');
        });

    // Organization routes
    Route::middleware(['auth', \App\Http\Middleware\OrganizationMiddleware::class])->prefix('organization')->name('organization.')->group(function () {
        Route::get('/dashboard', [OrganizationDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [OrganizationDashboardController::class, 'profile'])->name('profile');
        Route::get('/profile/setup', [OrganizationDashboardController::class, 'profileSetup'])->name('profile.setup');
        Route::put('/profile/update', [OrganizationDashboardController::class, 'profileUpdate'])->name('profile.update');
        
        // Training routes
        Route::get('/trainings', [OrganizationTrainingController::class, 'index'])->name('trainings');
        Route::get('/trainings/{training}', [OrganizationTrainingController::class, 'show'])->name('trainings.show');
    });
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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
    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{teacherId}', [AdminTeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacherId}/edit', [AdminTeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacherId}', [AdminTeacherController::class, 'update'])->name('teachers.update');
    Route::post('/teachers/{teacherId}/toggle-status', [AdminTeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');

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