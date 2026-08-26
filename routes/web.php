<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {

    // Single Dashboard - Role-based view
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ============================================
    // ADMIN ROUTES (Admin only)
    // ============================================
    Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/restore', [AdminController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{user}/force-delete', [AdminController::class, 'forceDelete'])->name('users.force-delete');

        // Role Management
        Route::get('/roles', [AdminController::class, 'roles'])->name('roles');

        // System Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });

    // ============================================
    // MANAGER ROUTES (Manager and Admin)
    // ============================================
    Route::middleware(['can:manager'])->prefix('manager')->name('manager.')->group(function () {
        // Projects
        Route::get('/projects', [ManagerController::class, 'projects'])->name('projects');

        // Team Management
        Route::get('/team', [ManagerController::class, 'team'])->name('team');

        // Reports
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
    });
});

// Auth routes
require __DIR__ . '/auth.php';
