<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\TrainingAssignmentController;
use App\Http\Controllers\LocationController;

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
Route::get('/test/admin/dashboard', [DashboardController::class, 'index'])->name('test.admin.dashboard');
Route::get('/test/admin/dashboard/data', [DashboardController::class, 'apiData'])->name('test.admin.dashboard.data');

// Location API Routes
Route::get('/api/districts/{region}', [LocationController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/wards/{district}', [LocationController::class, 'getWards'])->name('api.wards');

// Admin Training routes
Route::prefix('admin/trainings')->name('admin.trainings.')->group(function () {
    Route::get('/', [TrainingController::class, 'index'])->name('index');
    Route::get('/data', [TrainingController::class, 'getTrainings'])->name('data'); 
    Route::post('/', [TrainingController::class, 'store'])->name('store');
    
    // Training verification routes (before the show route to avoid conflicts)
    Route::put('/{trainingCode}/verify', [TrainingController::class, 'verify'])->name('verify');
    Route::put('/{trainingCode}/reject', [TrainingController::class, 'reject'])->name('reject');
    
    Route::get('/{trainingCode}', [TrainingController::class, 'show'])->name('show');
    Route::put('/{trainingCode}', [TrainingController::class, 'update'])->name('update');
    Route::delete('/{trainingCode}', [TrainingController::class, 'destroy'])->name('destroy');
    
    // Training Assignment routes
    Route::get('/{trainingCode}/assignment', [TrainingAssignmentController::class, 'show'])->name('assignment.show');
    Route::post('/{trainingCode}/update-phase', [TrainingAssignmentController::class, 'updatePhase'])->name('phase.update');
    
    Route::get('/{trainingCode}/available-teachers', [TrainingAssignmentController::class, 'getAvailableTeachers'])->name('teachers.available');
    Route::get('/{trainingCode}/assigned-teachers', [TrainingAssignmentController::class, 'getAssignedTeachers'])->name('teachers.assigned');
    Route::post('/{trainingCode}/assign-teachers', [TrainingAssignmentController::class, 'assignTeachers'])->name('teachers.assign');
    Route::delete('/{trainingCode}/remove-teacher/{teacherId}', [TrainingAssignmentController::class, 'removeTeacher'])->name('teachers.remove');
    
    Route::get('/{trainingCode}/available-facilitators', [TrainingAssignmentController::class, 'getAvailableFacilitators'])->name('facilitators.available');
    Route::get('/{trainingCode}/assigned-facilitators', [TrainingAssignmentController::class, 'getAssignedFacilitators'])->name('facilitators.assigned');
    Route::post('/{trainingCode}/assign-facilitators', [TrainingAssignmentController::class, 'assignFacilitators'])->name('facilitators.assign');
    Route::delete('/{trainingCode}/remove-facilitator/{facilitatorId}', [TrainingAssignmentController::class, 'removeFacilitator'])->name('facilitators.remove');
});

// Guest routes
Route::middleware('guest')->group(function () {
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

    // Registration Routes
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

        return view('auth.register.index', ['role' => ucfirst(str_replace('_', ' ', $role))]);
    })->name('register.role');

    Route::post('/register/store', [App\Http\Controllers\Auth\RegisterController::class, 'store'])
        ->name('register.store');

    Route::post('/register/organization/store', [App\Http\Controllers\Auth\RegisterController::class, 'storeOrganization'])
        ->name('register.organization.store');
});

// Authentication Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
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

// New routes for available teachers and facilitators
Route::get('/trainings/{trainingCode}/assignment', [TrainingAssignmentController::class, 'show'])->name('trainings.assignment');
Route::get('/trainings/{trainingCode}/available-teachers', [TrainingAssignmentController::class, 'getAvailableTeachers'])->name('trainings.available-teachers');
Route::get('/trainings/{trainingCode}/available-facilitators', [TrainingAssignmentController::class, 'getAvailableFacilitators'])->name('trainings.available-facilitators');
Route::post('/trainings/{trainingCode}/assign-teachers', [TrainingAssignmentController::class, 'assignTeachers'])->name('trainings.assign-teachers');
Route::post('/trainings/{trainingCode}/assign-facilitators', [TrainingAssignmentController::class, 'assignFacilitators'])->name('trainings.assign-facilitators');
Route::delete('/trainings/{trainingCode}/remove-teacher/{teacherId}', [TrainingAssignmentController::class, 'removeTeacher'])->name('trainings.remove-teacher');
Route::delete('/trainings/{trainingCode}/remove-facilitator/{facilitatorId}', [TrainingAssignmentController::class, 'removeFacilitator'])->name('trainings.remove-facilitator');