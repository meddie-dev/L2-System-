<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('pages.auth.login');  
});

// Auth Routes
Route::middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Admin Routes
Route::middleware('role:Admin')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
});

// Super Admin Routes
Route::middleware('role:Super Admin')->group(function () {
    // Super Admin Dashboard
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
    
});

// Staff Routes
Route::middleware('role:Staff')->group(function () {
    // Staff Dashboard
    Route::get('/staff/dashboard', [DashboardController::class, 'staffDashboard'])->name('staff.dashboard');
    
});

// Legal Routes
Route::view('/privacy-policy', 'pages.legal.privacyPolicy')->name('privacy-policy');
Route::view('/terms-and-conditions', 'pages.legal.termsAndConditions')->name('terms-and-conditions');

// Error Routes
Route::view('/401', 'components.errors.401')->name('401');
Route::view('/404', 'components.errors.404')->name('404');
Route::view('/500', 'components.errors.500')->name('500');
