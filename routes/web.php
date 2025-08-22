<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefuelingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return response()->json(["message" => 'cache cleared']);
});

Route::get('/template', function () {
    return view('template');
});

// Route for the combined financial chart
Route::get('/dashboard/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('cars', CarController::class);
Route::get('/cars/export/excel', [CarController::class, 'exportExcel'])->name('cars.export.excel');
Route::get('/cars/export/pdf', [CarController::class, 'exportPDF'])->name('cars.export.pdf');


Route::resource('drivers', DriverController::class);
Route::resource('insurances', InsuranceController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('maintenances', MaintenanceController::class);
Route::resource('refuelings', RefuelingController::class);
Route::resource('users', UserController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
