<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// Controllers
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\Portal\PortalLoginController;
use App\Http\Controllers\Auth\Portal\PortalRegisterController;
use App\Http\Controllers\MFA\TwoFactorController;
use App\Http\Controllers\Modules\VendorController;
// Notifications
use Illuminate\Notifications\DatabaseNotification;

/*-------------------------------------------------------------- 
# Default Route
--------------------------------------------------------------*/

Route::get('/', function () {
    return view('pages.auth.portal.login');
});

/*--------------------------------------------------------------
# Admin Route
--------------------------------------------------------------*/
Route::middleware('role:Admin')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
});

/*--------------------------------------------------------------
# Super Admin Route
--------------------------------------------------------------*/
Route::middleware('role:Super Admin')->group(function () {
    // Super Admin Dashboard
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
});

/*--------------------------------------------------------------
# Staff Route
--------------------------------------------------------------*/
Route::middleware('role:Staff')->group(function () {
    // Staff Dashboard
    Route::get('/staff/dashboard', [DashboardController::class, 'staffDashboard'])->name('staff.dashboard');

    // Vendor Management
    Route::resource('vendors', VendorController::class);
    Route::get('/vendors', [VendorController::class, 'index'])
        ->name('vendors.index');
    Route::post('/vendors/{vendor}/approve', [VendorController::class, 'approve'])
        ->name('vendors.approve')
        ->middleware('role:admin|super-admin');
    Route::post('/vendors/{vendor}/reject', [VendorController::class, 'reject'])
        ->name('vendors.reject')
        ->middleware('role:admin|super-admin');
});

/*--------------------------------------------------------------
# Driver Route
--------------------------------------------------------------*/
Route::middleware('role:Driver')->group(function () {
    // Driver Dashboard
    Route::get('/driver/dashboard', [DashboardController::class, 'driverDashboard'])->name('driver.dashboard');
});

/*--------------------------------------------------------------
# Auth Route
--------------------------------------------------------------*/
Route::middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::middleware([])->group(function () {
    // Forgot Password routes
    Route::controller(PasswordResetLinkController::class)->group(function () {
        Route::get('/forgot-password', 'create')->name('password.request');
        Route::post('/forgot-password', 'store')->name('password.email');
    });

    // Reset Password routes
    Route::controller(NewPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'create')->name('password.reset');
        Route::post('/reset-password', 'store')->name('password.update');
    });
});

/*--------------------------------------------------------------
# Two Factor Authentication Route
--------------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::get('two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('two-factor/send-code', [TwoFactorController::class, 'sendCode'])->name('two-factor.send');
    Route::post('two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::post('two-factor/verify-login', [TwoFactorController::class, 'verifyLogin'])->name('two-factor.verify-login');
    Route::post('two-factor/resend-code', [TwoFactorController::class, 'resendCode'])->name('two-factor.resend');
    Route::post('two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
});

Route::get('/notifications/{notification}', function (DatabaseNotification $notification) {
    $notification->markAsRead();

    return redirect()->route(
        (Auth::user()->hasRole('Super Admin')) ? 'superadmin.dashboard' : ((Auth::user()->hasRole('Admin')) ? 'admin.dashboard' : ((Auth::user()->hasRole('Staff')) ? 'staff.dashboard' : 'vendorPortal.dashboard'))
    );
})->name('notifications.show');


/*--------------------------------------------------------------
# Settings Route
--------------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/delete-view', [SettingsController::class, 'destroyView'])->name('settings.delete-view');
    Route::post('/settings/delete-account', [SettingsController::class, 'destroy'])->name('settings.delete-account');
});

/*--------------------------------------------------------------
# Add Ons Route
--------------------------------------------------------------*/
Route::view('/maps', 'pages.addOns.map')->name('map');
Route::view('/calendar', 'pages.addOns.calendar')->name('calendar');

/*--------------------------------------------------------------
# Vendor Portal Auth Route
--------------------------------------------------------------*/
Route::middleware(['web'])->group(function () {
    Route::get('/portal/login', [PortalLoginController::class, 'index'])->name('portal.login');
    Route::post('/portal/login', [PortalLoginController::class, 'login']);
    Route::get('/portal/register', [PortalRegisterController::class, 'index'])->name('portal.register');
    Route::post('/portal/register', [PortalRegisterController::class, 'register']);
    Route::post('/portal/logout', [PortalLoginController::class, 'destroy'])->name('portal.logout');
});

/*--------------------------------------------------------------
# Vendor Portal Route
--------------------------------------------------------------*/
Route::middleware(['role:Vendor'])->group(function () {
    // Vendor Portal Dashboard
    Route::get('/portal/vendor/dashboard', [DashboardController::class, 'vendorPortalDashboard'])
        ->name('vendorPortal.dashboard');
});

/*--------------------------------------------------------------
# Legal Route
--------------------------------------------------------------*/
Route::view('/privacy-policy', 'pages.legal.privacyPolicy')->name('privacy-policy');
Route::view('/terms-and-conditions', 'pages.legal.termsAndConditions')->name('terms-and-conditions');

/*--------------------------------------------------------------
# Error Routes
--------------------------------------------------------------*/
Route::view('/401', 'components.errors.401')->name('401');
Route::view('/404', 'components.errors.404')->name('404');
Route::view('/500', 'components.errors.500')->name('500');
