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
use App\Http\Controllers\Modules\AuditController;
use App\Http\Controllers\Modules\DocumentController;
use App\Http\Controllers\Modules\OrderController;
use App\Http\Controllers\Modules\VehicleReservationController;
use App\Http\Controllers\PaymentController;
use App\Models\Modules\Audit;
// Notifications
use Illuminate\Notifications\DatabaseNotification;

/*-------------------------------------------------------------- 
# Default Route
--------------------------------------------------------------*/

Route::get('/', function () {
    return view('pages.auth.portal.login');
});

/*--------------------------------------------------------------
# Super Admin Route
--------------------------------------------------------------*/
Route::middleware('role:Super Admin', 'active')->group(function () {
    // Super Admin Dashboard
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
  
});

/*--------------------------------------------------------------
# Admin Route
--------------------------------------------------------------*/
Route::middleware('role:Admin', 'active')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Vendor Management
    Route::get('/admin/dashboard/vendors', [AuditController::class, 'indexAdmin'])
        ->name('admin.vendors.manage');

    // Document Management
    Route::get('/admin/dashboard/document/review', [DocumentController::class, 'indexAdmin'])
        ->name('admin.document.manage');
   
    // Vehicle Reservation Management
   Route::get('/admin/dashboard/vehicleReservation/manage', [VehicleReservationController::class, 'indexAdmin'])
        ->name('admin.vehicleReservation.manage');
});

/*--------------------------------------------------------------
# Staff Route
--------------------------------------------------------------*/
Route::middleware('role:Staff', 'active')->group(function () {
    // Staff Dashboard
    Route::get('/staff/dashboard', [DashboardController::class, 'staffDashboard'])
        ->name('staff.dashboard');

    // Vendor Management
    Route::get('/staff/dashboard/vendor/request', [OrderController::class, 'manage'])
        ->name('staff.vendors.manage');
    Route::get('/staff/dashboard/vendor/{order}', [OrderController::class, 'show'])
        ->name('staff.vendors.show');
    Route::patch('/staff/dashboard/vendor/approve/{order}', [OrderController::class, 'approve'])
        ->name('staff.vendors.approve');
    Route::post('/vendors/{vendor}/reject', [OrderController::class, 'reject'])
        ->name('staff.vendors.reject');

    // Document Management
    Route::get('/staff/dashboard/document/submission', [DocumentController::class, 'manage'])
        ->name('staff.document.manage');
    Route::get('/staff/dashboard/document/submission/{order}', [DocumentController::class, 'show'])
        ->name('staff.document.show');
    Route::patch('/staff/dashboard/document/approve/{document}', [DocumentController::class, 'approve'])
        ->name('staff.document.approve');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])
        ->name('staff.document.reject');

    // Payment Management
    Route::get('/staff/dashboard/document/payment', [PaymentController::class, 'manage'])
        ->name('staff.payment.manage');
    Route::get('/staff/dashboard/document/payment/{order}', [PaymentController::class, 'show'])
        ->name('staff.payment.show');
    Route::patch('/staff/dashboard/payment/approve/{payment}', [PaymentController::class, 'approve'])
        ->name('staff.payment.approve');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])
        ->name('staff.payment.reject');

    // Vehicle Management
    Route::get('/staff/dashboard/vehicleReservation/order', [VehicleReservationController::class, 'indexOrder'])
        ->name('staff.vehicleReservation.indexOrder');
    Route::get('/staff/dashboard/vehicleReservation/create/order/{order}', [VehicleReservationController::class, 'createOrder'])
        ->name('staff.vehicleReservation.createOrder');
    Route::post('/staff/dashboard/vehicleReservation/order/{order}', [VehicleReservationController::class, 'storeOrder'])
        ->name('staff.vehicleReservation.storeOrder');

    Route::get('/staff/dashboard/vehicleReservation/vehicle', [VehicleReservationController::class, 'indexVehicle'])
        ->name('staff.vehicleReservation.indexVehicle');
    Route::get('/staff/dashboard/vehicleReservation/create/vehicle/{vehicleReservation}', [VehicleReservationController::class, 'createVehicle'])
        ->name('staff.vehicleReservation.createVehicle');
    Route::post('/staff/dashboard/vehicleReservation/vehicle/{vehicleReservation}', [VehicleReservationController::class, 'storeVehicle'])
        ->name('staff.vehicleReservation.storeVehicle');
});

/*--------------------------------------------------------------
# Vendor Portal Route
--------------------------------------------------------------*/
Route::middleware(['role:Vendor', 'active'])->group(function () {
    // Vendor Portal Dashboard
    Route::get('/portal/vendor/dashboard', [DashboardController::class, 'vendorPortalDashboard'])
        ->name('vendorPortal.dashboard');

    // Order Management
    Route::resource('vendors', OrderController::class);
    Route::get('/portal/vendor/dashboard/order', [OrderController::class, 'index'])
        ->name('vendorPortal.order');
    Route::get('/portal/vendor/dashboard/order/new', [OrderController::class, 'create'])
        ->name('vendorPortal.order.new');
    Route::post('/portal/vendor/dashboard/order/{user}', [OrderController::class, 'store'])
        ->name('vendorPortal.order.store');
    Route::get('/portal/vendor/dashboard/order/{order}', [OrderController::class, 'edit'])
        ->name('vendorPortal.order.edit');
    Route::patch('/portal/vendor/dashboard/order/{order}', [OrderController::class, 'update'])
        ->name('vendorPortal.order.update');
    Route::get('/portal/vendor/dashboard/order/approved/{order}', [OrderController::class, 'details'])
        ->name('vendorPortal.order.details');

    // Document Management
    Route::get('/portal/vendor/dashboard/document', [DocumentController::class, 'index'])
        ->name('vendorPortal.order.document');
    Route::get('/portal/vendor/dashboard/order/document/new/{order}', [DocumentController::class, 'create'])
        ->name('vendorPortal.order.document.new');
    Route::post('/portal/vendor/dashboard/order/document/{order}', [DocumentController::class, 'store'])
        ->name('vendorPortal.order.document.store');
    Route::get('/portal/vendor/dashboard/order/document/{order}', [DocumentController::class, 'edit'])
        ->name('vendorPortal.order.document.edit');
    Route::patch('/portal/vendor/dashboard/order/document/{order}', [DocumentController::class, 'update'])
        ->name('vendorPortal.order.document.update');
    Route::get('/portal/vendor/dashboard/document/details/{document}', [DocumentController::class, 'details'])
        ->name('vendorPortal.document.details');

    // Payment Management
    Route::get('/portal/vendor/dashboard/payment', [PaymentController::class, 'index'])
        ->name('vendorPortal.order.payment');
    Route::get('/portal/vendor/dashboard/order/payment/new/{order}', [PaymentController::class, 'create'])
        ->name('vendorPortal.order.payment.new');
    Route::post('/portal/vendor/dashboard/order/payment/{order}', [PaymentController::class, 'store'])
        ->name('vendorPortal.order.payment.store');
    Route::get('/portal/vendor/dashboard/order/payment/{order}', [PaymentController::class, 'edit'])
        ->name('vendorPortal.order.payment.edit');
    Route::patch('/portal/vendor/dashboard/order/payment/{order}', [PaymentController::class, 'update'])
        ->name('vendorPortal.order.payment.update');
    Route::get('/portal/vendor/dashboard/payment/details/{payment}', [PaymentController::class, 'details'])
        ->name('vendorPortal.payment.details');

    // Vehicle Reservation Management
    Route::get('/portal/vendor/dashboard/vehicleReservation', [VehicleReservationController::class, 'vendorIndex'])
        ->name('vendorPortal.vehicleReservation');
    Route::get('/portal/vendor/dashboard/vehicleReservation/new', [VehicleReservationController::class, 'vendorCreate'])
        ->name('vendorPortal.vehicleReservation.new');
    Route::post('/portal/vendor/dashboard/vehicleReservation/{user}', [VehicleReservationController::class, 'vendorStore'])
        ->name('vendorPortal.vehicleReservation.store');
    Route::get('/portal/vendor/dashboard/vehicleReservation/{vehicleReservation}', [VehicleReservationController::class, 'vendorEdit'])
        ->name('vendorPortal.vehicleReservation.edit');
    Route::patch('/portal/vendor/dashboard/vehicleReservation/{vehicleReservation}', [VehicleReservationController::class, 'vendorUpdate'])
        ->name('vendorPortal.vehicleReservation.update');
    Route::get('/portal/vendor/dashboard/vehicleReservation/details/{vehicleReservation}', [VehicleReservationController::class, 'vendorDetails'])
        ->name('vendorPortal.vehicleReservation.details');
});

/*--------------------------------------------------------------
# Driver Route
--------------------------------------------------------------*/
Route::middleware('role:Driver', 'active')->group(function () {
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
Route::middleware(['auth', 'active'])->group(function () {
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
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])
        ->name('settings.update');
    Route::get('/settings/delete-view', [SettingsController::class, 'destroyView'])
        ->name('settings.delete-view');
    Route::post('/settings/delete-account', [SettingsController::class, 'destroy'])
        ->name('settings.delete-account');
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
