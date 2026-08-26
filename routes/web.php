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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Rental routes
    Route::prefix('rentals')->name('rentals.')->group(function () {
        Route::get('/', [RentalController::class, 'index'])->name('index');
        Route::get('/create/{car}', [RentalController::class, 'create'])->name('create');
        Route::post('/{car}', [RentalController::class, 'store'])->name('store');
        Route::get('/{rental}', [RentalController::class, 'show'])->name('show');
        Route::patch('/{rental}/cancel', [RentalController::class, 'cancel'])->name('cancel');
        Route::patch('/{rental}/confirm', [RentalController::class, 'confirm'])->name('confirm');
        Route::patch('/{rental}/complete', [RentalController::class, 'complete'])->name('complete');
    });

    // Payment routes
    Route::prefix('payment')->name('payments.')->group(function () {
        Route::get('/{payment}', [PaymentController::class, 'process'])->name('process');
        Route::post('/{payment}/confirm', [PaymentController::class, 'confirm'])->name('confirm');
        Route::patch('/{payment}/refund', [PaymentController::class, 'refund'])->name('refund');
        Route::get('/{payment}/retry', [PaymentController::class, 'retry'])->name('retry');
    });

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
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/projects', [ManagerController::class, 'projects'])->name('projects');
        Route::get('/team', [ManagerController::class, 'team'])->name('team');
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
    });

    // Admin routes
    Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/cars', [AdminController::class, 'cars'])->name('cars');
        Route::patch('/cars/{car}/approve', [AdminController::class, 'approveCar'])->name('cars.approve');
        Route::delete('/cars/{car}/reject', [AdminController::class, 'rejectCar'])->name('cars.reject');
        Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
        Route::get('/rentals', [AdminController::class, 'rentals'])->name('rentals');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    });
});

// Authentication routes
require __DIR__ . '/auth.php';
