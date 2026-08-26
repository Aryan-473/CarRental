<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('cars.index');
});

// Car routes (public)
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/search', [CarController::class, 'search'])->name('cars.search');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rental routes
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create/{car}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals/{car}', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::patch('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');

    // Admin rental management routes
    Route::patch('/rentals/{rental}/confirm', [RentalController::class, 'confirm'])->name('rentals.confirm');
    Route::patch('/rentals/{rental}/complete', [RentalController::class, 'complete'])->name('rentals.complete');

    // Payment routes
    Route::get('/payment/{payment}', [PaymentController::class, 'process'])->name('payments.process');
    Route::post('/payment/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::patch('/payment/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');

    Route::get('/payment/{payment}/retry', [PaymentController::class, 'retry'])->name('payments.retry');  // ADD THIS

    // Vendor routes
    Route::middleware(['can:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
        Route::get('/cars', [VendorController::class, 'cars'])->name('cars');
        Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
        Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
        Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
        Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
        Route::get('/rentals', [VendorController::class, 'rentals'])->name('rentals');
    });

    // Manager routes
    Route::middleware(['can:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/projects', [ManagerController::class, 'projects'])->name('projects');
        Route::get('/team', [ManagerController::class, 'team'])->name('team');
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
    });

    // Admin routes
    Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/cars', [AdminController::class, 'cars'])->name('cars');
        Route::patch('/cars/{car}/approve', [AdminController::class, 'approveCar'])->name('cars.approve');
        Route::delete('/cars/{car}/reject', [AdminController::class, 'rejectCar'])->name('cars.reject');
        Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
        Route::get('/rentals', [AdminController::class, 'rentals'])->name('rentals');
    });
});

// Authentication routes
require __DIR__ . '/auth.php';
