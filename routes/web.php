<?php

use App\Http\Controllers\AddOnsController;
use App\Http\Controllers\AssetController;
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
use App\Http\Controllers\BackupController;
use App\Http\Controllers\FraudDetectionController;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MFA\TwoFactorController;
use App\Http\Controllers\Modules\AuditController;
use App\Http\Controllers\Modules\DocumentController;
use App\Http\Controllers\Modules\FleetController;
use App\Http\Controllers\Modules\OrderController;
use App\Http\Controllers\Modules\VehicleReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TripTicketController;
use App\Http\Controllers\WarehouseController;
use App\Models\Modules\Order;
// Notifications
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Http;

/*-------------------------------------------------------------- 
# Default Route
--------------------------------------------------------------*/

Route::get('/', function () {
    return view('pages.auth.portal.login');
});

Route::get('/test-python', [FraudDetectionController::class, 'testPython']);

/*--------------------------------------------------------------
# Super Admin Route
--------------------------------------------------------------*/
Route::middleware('role:Super Admin', 'active')->group(function () {
    // Super Admin Dashboard
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superAdminDashboard'])
        ->name('superadmin.dashboard');

    // Vendor Management
    Route::get('/superadmin/vendor/profiles', [OrderController::class, 'indexSA'])
        ->name('superadmin.vendors.index');
    Route::get('/superadmin/vendor/profiles/{user}', [OrderController::class, 'showSA'])
        ->name('superadmin.vendors.show');

    // Audit Management
    Route::get('/superadmin/audit/security', [AuditController::class, 'indexSecuritySA'])
        ->name('superadmin.audit.index');
    Route::get('/superadmin/audit/security/{user}', [AuditController::class, 'showSecuritySA'])
        ->name('superadmin.audit.show');
    Route::patch('/superadmin/audit/security/{user}', [AuditController::class, 'penaltySecuritySA'])
        ->name('superadmin.audit.penalty');

    // Document Management
    Route::get('/superadmin/document/track', [DocumentController::class, 'manageSA'])
        ->name('superadmin.document.manage');

    // Vehicle Reservation
    Route::get('/superadmin/vehicle/reservation', [VehicleReservationController::class, 'indexSA'])
        ->name('superadmin.vehicleReservation.index');
    Route::get('/superadmin/vehicle/reservation/{vehicleReservation}', [VehicleReservationController::class, 'showSA'])
        ->name('superadmin.vehicleReservation.show');

    // Fleet Management -> Driver
    Route::get('/superadmin/fleet/driver', [FleetController::class, 'driverIndexSA'])
        ->name('superadmin.fleet.driver.index');
    Route::get('/superadmin/fleet/driver/details/{user}', [FleetController::class, 'driverDetailsSA'])
        ->name('superadmin.fleet.driver.details');

    // Fleet Management -> Fuel
    Route::get('/superadmin/fleet/fuel', [FleetController::class, 'fuelIndexSA'])
        ->name('superadmin.fleet.fuel.index');
    Route::get('/superadmin/fleet/fuel/details/{fuel}', [FleetController::class, 'fuelDetailsSA'])
        ->name('superadmin.fleet.fuel.details');
    Route::patch('/superadmin/dashboard/fleet/fuel/{fuel}', [FleetController::class, 'fuelUpdateSA'])
        ->name('superadmin.fleet.fuel.update');

    // Fleet Management -> Vehicle
    Route::get('/superadmin/fleet/vehicle', [FleetController::class, 'vehicleIndexSA'])
        ->name('superadmin.fleet.vehicle.index');
    Route::get('/superadmin/dashboard/fleet/vehicle/new', [FleetController::class, 'createSA'])
        ->name('superadmin.fleet.vehicle.new');
    Route::post('/superadmin/dashboard/fleet/vehicle', [FleetController::class, 'storeSA'])
        ->name('superadmin.fleet.store');
    Route::get('/superadmin/dashboard/fleet/vehicle/{vehicle}', [FleetController::class, 'editSA'])
        ->name('superadmin.fleet.edit');
    Route::patch('/superadmin/dashboard/fleet/vehicle/{vehicle}', [FleetController::class, 'updateSA'])
        ->name('superadmin.fleet.update');
    Route::get('/superadmin/dashboard/fleet/vehicle/details/{vehicle }', [FleetController::class, 'details'])
        ->name('superadmin.fleet.details');
  
    // Asset Management
    Route::get('/superadmin/asset/manage', [AssetController::class, 'indexSA'])
        ->name('superadmin.asset.index');
    Route::get('/superadmin/asset/manage/details/{vehicle}', [AssetController::class, 'detailsSA'])
        ->name('superadmin.asset.details');

    // Fraud Detection
    Route::get('/superadmin/fraud/activity', [FraudDetectionController::class, 'showFraudResults'])
        ->name('superadmin.fraud-detection');

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
    Route::get('/admin/dashboard/vendor/{user}', [AuditController::class, 'showVendor'])
        ->name('admin.vendors.show');

    Route::get('/admin/dashboard/vendor/{user}/order/{order}', [AuditController::class, 'showOrder'])
        ->name('admin.vendors.order.show');
    Route::patch('/admin/dashboard/vendor/{user}/order/{order}/approved', [AuditController::class, 'approvedOrder'])
        ->name('admin.vendors.order.approved');
    Route::patch('/admin/dashboard/vendor/{user}/order/{order}/rejected', [AuditController::class, 'rejectedOrder'])
        ->name('admin.vendors.order.rejected');

    Route::get('/admin/dashboard/vendor/{user}/document/{document}', [AuditController::class, 'showDocument'])
        ->name('admin.vendors.document.show');
    Route::patch('/admin/dashboard/vendor/{user}/document/{document}/approved', [AuditController::class, 'approvedDocument'])
        ->name('admin.vendors.document.approved');
    Route::patch('/admin/dashboard/vendor/{user}/document/{document}/rejected', [AuditController::class, 'rejectedDocument'])
        ->name('admin.vendors.document.rejected');
    
    Route::get('/admin/dashboard/vendor/{user}/payment/{payment}', [AuditController::class, 'showPayment'])
        ->name('admin.vendors.payment.show');
    Route::patch('/admin/dashboard/vendor/{user}/payment/{payment}/approved', [AuditController::class, 'approvedPayment'])
        ->name('admin.vendors.payment.approved');
    Route::patch('/admin/dashboard/vendor/{user}/payment/{payment}/rejected', [AuditController::class, 'rejectedPayment'])
        ->name('admin.vendors.payment.rejected');
    
    Route::get('/admin/dashboard/vendor/{user}/reservation/{vehicleReservation}', [AuditController::class, 'showVehicleReservation'])
        ->name('admin.vendors.reservation.show');
    Route::patch('/admin/dashboard/vendor/{user}/reservation/{vehicleReservation}/approved', [AuditController::class, 'approvedVehicleReservation'])
        ->name('admin.vendors.vehicleReservation.approved');
    Route::patch('/admin/dashboard/vendor/{user}/reservation/{vehicleReservation}/rejected', [AuditController::class, 'rejectedVehicleReservation'])
        ->name('admin.vendors.vehicleReservation.rejected');


    // Audit Management
    Route::get('/admin/dashboard/audit/assessment', [AuditController::class, 'indexReportAdmin'])
        ->name('admin.audit.index');
    Route::get('/admin/dashboard/audit/assessment/details/{incidentReport}', [AuditController::class, 'detailsAdmin'])
        ->name('admin.audit.details');
    Route::post('/admin/dashboard/audit/assessment/{incidentReport}/approved', [AuditController::class, 'approved'])
        ->name('admin.audit.report.approved');
    Route::post('/admin/dashboard/audit/assessment/{incidentReport}/rejected', [AuditController::class, 'rejectedAdmin'])
        ->name('admin.audit.report.rejected');

    Route::get('/admin/dashboard/audit/review', [AuditController::class, 'indexActivity'])
        ->name('admin.audit.activity.index');
    Route::get('/admin/dashboard/audit/staff/{user}', [AuditController::class, 'staffActivity'])
        ->name('admin.audit.staff.activity');
    Route::get('/admin/dashboard/audit/driver/{user}', [AuditController::class, 'driverActivity'])
        ->name('admin.audit.driver.activity');


    // Document Management
    Route::get('/admin/dashboard/document/manage', [DocumentController::class, 'manageAdmin'])
        ->name('admin.document.manage');
   
    // Vehicle Reservation Management
    Route::get('/admin/dashboard/vehicleReservation/manage', [VehicleReservationController::class, 'indexAdmin'])
        ->name('admin.vehicleReservation.manage');
    Route::get('/admin/dashboard/vehicleReservation/{vehicleReservation}', [VehicleReservationController::class, 'showAdmin'])
        ->name('admin.vehicleReservation.show');
    Route::patch('/admin/dashboard/vehicleReservation/approve/{vehicleReservation}', [VehicleReservationController::class, 'approve'])
        ->name('admin.vehicleReservation.approve');
    Route::patch('/admin/dashboard/vehicleReservation/scheduled/{vehicleReservation}', [VehicleReservationController::class, 'approveVehisleReservation'])
        ->name('admin.vehicleReservation.scheduled');

    Route::patch('/admin/dashboard/vehicleReservation/reject/{vehicleReservation}', [VehicleReservationController::class, 'reject'])
        ->name('admin.vehicleReservation.reject');

    // Fleet Management -> Vehicle
    Route::get('/admin/dashboard/fleet/vehicle', [FleetController::class, 'index'])
        ->name('admin.fleet.index');
    Route::get('/admin/dashboard/fleet/vehicle/new', [FleetController::class, 'create'])
        ->name('admin.fleet.vehicle.new');
    Route::post('/admin/dashboard/fleet/vehicle', [FleetController::class, 'store'])
        ->name('admin.fleet.store');
    Route::get('/admin/dashboard/fleet/vehicle/{vehicle}', [FleetController::class, 'edit'])
        ->name('admin.fleet.edit');
    Route::patch('/admin/dashboard/fleet/vehicle/{vehicle}', [FleetController::class, 'update'])
        ->name('admin.fleet.update');
    Route::get('/admin/dashboard/fleet/vehicle/details/{vehicle }', [FleetController::class, 'details'])
        ->name('admin.fleet.details');

    // Fleet Management -> Driver
    Route::get('/admin/dashboard/fleet/driver', [FleetController::class, 'driverIndex'])
        ->name('admin.fleet.driver.index');
    Route::get('/admin/dashboard/fleet/driver/details/{user}', [FleetController::class, 'driverDetails'])
        ->name('admin.fleet.driver.details');
    Route::patch('/admin/dashboard/fleet/driver/{user}', [FleetController::class, 'driverUpdate'])
        ->name('admin.fleet.driver.update');

    // Fleet Management -> Fuel
    Route::get('/admin/dashboard/fleet/fuel', [FleetController::class, 'fuelIndex'])
        ->name('admin.fleet.fuel.index');
    Route::get('/admin/dashboard/fleet/fuel/details/{fuel}', [FleetController::class, 'fuelDetails'])
        ->name('admin.fleet.fuel.details');
    Route::patch('/admin/dashboard/fleet/fuel/{fuel}', [FleetController::class, 'fuelUpdate'])
        ->name('admin.fleet.fuel.update');

    // Warehouse
    Route::get('/admin/dashboard/warehouse/inventory', [WarehouseController::class, 'index'])
        ->name('admin.warehouse.index');
    Route::get('/admin/dashboard/warehouse/inventory/request/{product}', [WarehouseController::class, 'create'])
        ->name('admin.warehouse.request');
    Route::patch('/admin/dashboard/warehouse/inventory/update/{product}', [WarehouseController::class, 'update'])
        ->name('admin.warehouse.update');

    // Asset Management
    Route::get('/admin/dashboard/asset', [AssetController::class, 'index'])
        ->name('admin.asset.index');
    Route::get('/admin/dashboard/asset/details/{vehicle}', [AssetController::class, 'details'])
        ->name('admin.asset.details');

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
        ->name('staff.vendors.show')
        ->can('viewOrder','order');
    Route::patch('/staff/dashboard/vendor/approve/{order}', [OrderController::class, 'approve'])
        ->name('staff.vendors.approve');
    Route::patch('/staff/dashboard/vendor/reject/{order}', [OrderController::class, 'reject'])
        ->name('staff.vendors.reject');

    // Audit Management
    Route::get('/staff/dashboard/audit/report', [AuditController::class, 'indexStaff'])
        ->name('staff.audit.index');
    Route::get('/staff/dashboard/audit/report/details/{incidentReport}', [AuditController::class, 'detailsStaff'])
        ->name('staff.audit.details')
        ->can('viewIncident','incidentReport');
    Route::patch('/staff/dashboard/audit/report/{incidentReport}/reviewed', [AuditController::class, 'reviewed'])
        ->name('staff.audit.report.reviewed');
    Route::patch('/staff/dashboard/audit/report/{incidentReport}/rejected', [AuditController::class, 'rejected'])
        ->name('staff.audit.report.rejected');

    // Document Management
    Route::get('/staff/dashboard/document/submission', [DocumentController::class, 'manage'])
        ->name('staff.document.manage');
    Route::get('/staff/dashboard/document/submission/{document}', [DocumentController::class, 'show'])
        ->name('staff.document.show')
        ->can('viewDocument','document');
    Route::patch('/staff/dashboard/document/approve/{document}', [DocumentController::class, 'approve'])
        ->name('staff.document.approve');
    Route::patch('/staff/dashboard/document/reject/{document}', [DocumentController::class, 'reject'])
        ->name('staff.document.reject');

    // Payment Management
    Route::get('/staff/dashboard/document/payment', [PaymentController::class, 'manage'])
        ->name('staff.payment.manage');
    Route::get('/staff/dashboard/document/payment/{payment}', [PaymentController::class, 'show'])
        ->name('staff.payment.show')
        ->can('viewPayment','payment');
    Route::patch('/staff/dashboard/payment/approve/{payment}', [PaymentController::class, 'approve'])
        ->name('staff.payment.approve');
    Route::patch('/staff/dashboard/payment/reject/{payment}', [PaymentController::class, 'reject'])
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
    Route::get('/staff/dashboard/vehicleReservation/vehicle/details/{vehicleReservation}', [VehicleReservationController::class, 'detailsVehicle'])
        ->name('staff.vehicleReservation.detailsVehicle')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::patch('/staff/dashboard/vehicleReservation/vehicle/approve/{vehicleReservation}', [VehicleReservationController::class, 'reviewVehicle'])
        ->name('staff.vehicleReservation.reviewVehicle');
    Route::patch('/staff/dashboard/vehicleReservation/vehicle/reject/{vehicleReservation}', [VehicleReservationController::class, 'rejectVehicle'])
        ->name('staff.vehicleReservation.rejectVehicle');

    // Fleet Management
    Route::get('/staff/dashboard/fleet/maintenance', [MaintenanceController::class, 'index'])
        ->name('staff.fleet.index');
    Route::get('/staff/dashboard/fleet/vehicle/show/{vehicle}', [MaintenanceController::class, 'show'])
        ->name('staff.fleet.vehicle.show');
    Route::get('/staff/dashboard/fleet/vehicle/view/{maintenance}', [MaintenanceController::class, 'view'])
        ->name('staff.fleet.vehicle.view');
    Route::patch('/staff/dashboard/fleet/vehicle/mark-as-available/{maintenance}', [MaintenanceController::class, 'markAsAvailable'])
        ->name('staff.fleet.vehicle.markAsAvailable');

  
});

/*--------------------------------------------------------------
# Vendor Portal Route
--------------------------------------------------------------*/
Route::middleware(['role:Vendor', 'active'])->group(function () {
    // Vendor Portal Dashboard
    Route::get('/portal/vendor/dashboard', [DashboardController::class, 'vendorPortalDashboard'])
        ->name('vendorPortal.dashboard');

    // Pdf 
    Route::get('/portal/vendor/dashboard/pdf/invoice/{order}', [PaymentController::class, 'paymentPdf'])
        ->name('vendorPortal.payment.pdf');
    // Pdf 
    Route::get('/portal/vendor/dashboard/pdf/booking/{vehicleReservation}', [PaymentController::class, 'paymentPdfVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.pdf');

    // Order Management
    Route::resource('vendors', OrderController::class);
    Route::get('/portal/vendor/dashboard/order', [OrderController::class, 'index'])
        ->name('vendorPortal.order');
    Route::get('/portal/vendor/dashboard/order/new', [OrderController::class, 'create'])
        ->name('vendorPortal.order.new');
    Route::post('/portal/vendor/dashboard/order/{user}', [OrderController::class, 'store'])
        ->name('vendorPortal.order.store');
    Route::get('/portal/vendor/dashboard/order/{order}', [OrderController::class, 'edit'])
        ->name('vendorPortal.order.edit')
        ->can('viewOrder','order');
    Route::patch('/portal/vendor/dashboard/order/{order}', [OrderController::class, 'update'])
        ->name('vendorPortal.order.update')
        ->can('viewOrder','order');
    Route::get('/portal/vendor/dashboard/order/details/{order}', [OrderController::class, 'details'])
        ->name('vendorPortal.order.details')
        ->can('viewOrder','order');

    // Document Management
    Route::get('/portal/vendor/dashboard/document', [DocumentController::class, 'index'])
        ->name('vendorPortal.order.document');
    Route::get('/portal/vendor/dashboard/order/document/new/{order}', [DocumentController::class, 'create'])
        ->name('vendorPortal.order.document.new')
        ->can('viewOrder','order');
    Route::post('/portal/vendor/dashboard/order/document/{order}', [DocumentController::class, 'store'])
        ->name('vendorPortal.order.document.store')
        ->can('viewOrder','order');
    Route::get('/portal/vendor/dashboard/order/document/{order}', [DocumentController::class, 'edit'])
        ->name('vendorPortal.order.document.edit')
        ->can('viewOrder','order');
    Route::patch('/portal/vendor/dashboard/order/document/{order}', [DocumentController::class, 'update'])
        ->name('vendorPortal.order.document.update')
        ->can('viewOrder','order');
    Route::get('/portal/vendor/dashboard/document/details/{document}', [DocumentController::class, 'details'])
        ->name('vendorPortal.document.details')
        ->can('viewDocument','document');

    // Payment Management -> Order  
    Route::get('/portal/vendor/dashboard/payment/invoice', [PaymentController::class, 'index'])
        ->name('vendorPortal.order.payment');
    Route::get('/portal/vendor/dashboard/order/payment/new/{order}', [PaymentController::class, 'create'])
        ->name('vendorPortal.order.payment.new')
        ->can('viewOrder','order');
    Route::post('/portal/vendor/dashboard/order/payment/{order}', [PaymentController::class, 'store'])
        ->name('vendorPortal.order.payment.store')
        ->can('viewOrder','order');
    Route::get('/portal/vendor/dashboard/order/payment/{order}', [PaymentController::class, 'edit'])
        ->name('vendorPortal.order.payment.edit')
        ->can('viewOrder','order');
    Route::patch('/portal/vendor/dashboard/order/payment/{order}', [PaymentController::class, 'update'])
        ->name('vendorPortal.order.payment.update')
        ->can('viewOrder','order');
    Route::get('/portal/vendor/dashboard/payment/details/invoice/{payment}', [PaymentController::class, 'details'])
        ->name('vendorPortal.payment.details')
        ->can('viewPayment','payment');

    // Vehicle Reservation Management
    Route::get('/portal/vendor/dashboard/vehicleReservation', [VehicleReservationController::class, 'vendorIndex'])
        ->name('vendorPortal.vehicleReservation');
    Route::get('/portal/vendor/dashboard/vehicleReservation/new', [VehicleReservationController::class, 'vendorCreate'])
        ->name('vendorPortal.vehicleReservation.new');
    Route::post('/portal/vendor/dashboard/vehicleReservation/{user}', [VehicleReservationController::class, 'vendorStore'])
        ->name('vendorPortal.vehicleReservation.store');
    Route::get('/portal/vendor/dashboard/vehicleReservation/edit/{vehicleReservation}', [VehicleReservationController::class, 'vendorEdit'])
        ->name('vendorPortal.vehicleReservation.edit')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::patch('/portal/vendor/dashboard/vehicleReservation/update/{vehicleReservation}', [VehicleReservationController::class, 'vendorUpdate'])
        ->name('vendorPortal.vehicleReservation.update')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::get('/portal/vendor/dashboard/vehicleReservation/details/{vehicleReservation}', [VehicleReservationController::class, 'vendorDetails'])
        ->name('vendorPortal.vehicleReservation.details')
        ->can('viewVehicleReservation','vehicleReservation');

    Route::get('/portal/vendor/dashboard/vehicleReservation/status', [VehicleReservationController::class, 'vendorStatusIndex'])
        ->name('vendorPortal.vehicleReservation.status');
    Route::get('/portal/vendor/dashboard/vehicleReservation/status/details/{tripTicket}', [VehicleReservationController::class, 'vendorStatusDetails'])
    ->name('vendorPortal.vehicleReservation.status.details');


    // Payment Management -> Vehicle Reservation
    Route::get('/portal/vendor/dashboard/payment/booking', [PaymentController::class, 'indexVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.payment');
    Route::get('/portal/vendor/dashboard/vehicleReservation/payment/new/{vehicleReservation}', [PaymentController::class, 'createVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.payment.new')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::post('/portal/vendor/dashboard/vehicleReservation/payment/{vehicleReservation}', [PaymentController::class, 'storeVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.payment.store')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::get('/portal/vendor/dashboard/vehicleReservation/payment/{vehicleReservation}', [PaymentController::class, 'editVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.payment.edit')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::patch('/portal/vendor/dashboard/vehicleReservation/payment/{vehicleReservation}', [PaymentController::class, 'updateVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.payment.update')
        ->can('viewVehicleReservation','vehicleReservation');
    Route::get('/portal/vendor/dashboard/payment/details/booking/{payment}', [PaymentController::class, 'detailsVehicleReservation'])
        ->name('vendorPortal.vehicleReservation.payment.details')
        ->can('viewPayment','payment');

    // Card Management
    Route::get('/portal/vendor/dashboard/card/in-transit', [TripTicketController::class, 'vendorInTransitIndex'])
        ->name('vendorPortal.card.inTransit');
    Route::get('/portal/vendor/dashboard/card/scheduled', [TripTicketController::class, 'vendorScheduledIndex'])
        ->name('vendorPortal.card.scheduled');
    Route::get('/portal/vendor/dashboard/card/delivered', [TripTicketController::class, 'vendorDeliveredIndex'])
        ->name('vendorPortal.card.delivered');
    Route::get('/portal/vendor/dashboard/card/details/{id}', [TripTicketController::class, 'vendorInTransitDetails'])
        ->name('vendorPortal.card.details');

    // Driver Rate Management
    Route::get('/portal/vendor/dashboard/card/details/{id}/make-rate', [TripTicketController::class, 'makeRate'])
        ->name('vendorPortal.card.makeRate');
    Route::post('/portal/vendor/dashboard/card/details/{id}/rate', [TripTicketController::class, 'rateTrip'])
        ->name('tripTicket.rate');
});

/*--------------------------------------------------------------
# Driver Route
--------------------------------------------------------------*/
Route::middleware('role:Driver', 'active')->group(function () {
    // Driver Dashboard
    Route::get('/driver/dashboard', [DashboardController::class, 'driverDashboard'])
        ->name('driver.dashboard');

    // Calendar
    Route::get('/calendarDriver', [AddOnsController::class, 'calendarDriver'])
        ->name('calendarDriver');

    // Pdf 
    Route::get('/driver/dashboard/pdf/{vehicleReservation}', [FleetController::class, 'driverTripTicketPdf'])
        ->name('driver.tripTicket.pdf')
        ->can('viewvehicleReservation','vehicleReservation');
    Route::get('/driver/dashboard/pdf/booking/{vehicleReservation}', [FleetController::class, 'driverTripTicketBookingPdf'])
        ->name('driver.tripTicketBooking.pdf')
        ->can('viewvehicleReservation','vehicleReservation');
    

    // Task Management
    Route::get('/driver/dashboard/task', [FleetController::class, 'driverTask'])
        ->name('driver.task');
    Route::get('/driver/dashboard/task/{vehicleReservation}', [FleetController::class, 'driverTaskDetails'])
        ->name('driver.task.details')
        ->can('viewVehicleReservation','vehicleReservation');

    // Audit Management
    Route::get('/driver/dashboard/audit/report', [IncidentReportController::class, 'index'])
        ->name('driver.audit.report');
    Route::get('/driver/dashboard/audit/report/details/{incidentReport}', [IncidentReportController::class, 'details'])
        ->name('driver.audit.report.details')
        ->can('viewIncidentReport','incidentReport');

    // Fleet - Trip Management
    Route::get('/driver/dashboard/fleet/ticket', [FleetController::class, 'driverTrip'])
        ->name('driver.trip');
    Route::get('/driver/dashboard/fleet/ticket/{tripTicket}', [FleetController::class, 'driverTripDetails'])
        ->name('driver.trip.details')
        ->can('viewTripTicket','tripTicket');
    Route::patch('/driver/dashboard/fleet/ticket/{id}/inTransit', [TripTicketController::class, 'markAsInTransit'])
        ->name('driver.trip.inTransit');
    Route::patch('/driver/dashboard/fleet/ticket/{id}/deliver', [TripTicketController::class, 'markAsDelivered'])
        ->name('driver.trip.deliver');
    Route::get('/driver/dashboard/fleet/ticket/{tripTicket}/report', [IncidentReportController::class, 'create'])
        ->name('driver.trip.report');
    Route::post('/driver/dashboard/fleet/ticket/{id}', [IncidentReportController::class, 'store'])
        ->name('driver.trip.report.store');
    

    // Fleet - Card Management
    Route::get('/driver/dashboard/fleet/card', [FleetController::class, 'driverCard'])
        ->name('driver.card');
    Route::get('/driver/dashboard/fleet/card/{fleetCard}', [FleetController::class, 'driverCardDetails'])
        ->name('driver.card.details')
        ->can('viewFleetCard','fleetCard');
    
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
        (Auth::user()->hasRole('Super Admin')) ? 'superadmin.dashboard' : ((Auth::user()->hasRole('Admin')) ? 'admin.dashboard' : ((Auth::user()->hasRole('Staff')) ? 'staff.dashboard' : ((Auth::user()->hasRole('Vendor')) ? 'vendorPortal.dashboard' : 'driver.dashboard')))
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
Route::get('/maps', [AddOnsController::class, 'map'])
    ->name('map')
    ->middleware(['auth', 'active']);
Route::get('/calendar', [AddOnsController::class, 'calendar'])
    ->name('calendar')
    ->middleware(['auth', 'active']);

// Backups Database
Route::get('/backups', [BackupController::class, 'listBackups'])
    ->name('backups')
    ->middleware(['auth','role:Super Admin', 'active']);
Route::post('/restore-backup', [BackupController::class, 'restoreBackup'])
    ->name('restore-backup')
    ->middleware(['auth','role:Super Admin', 'active']);

/*--------------------------------------------------------------
# Vendor Portal Auth Route
--------------------------------------------------------------*/
Route::middleware(['web'])->group(function () {
    Route::get('/portal/login', [PortalLoginController::class, 'index'])
        ->name('portal.login');
    Route::post('/portal/login', [PortalLoginController::class, 'login'])
        ->name('portal.login');
    Route::get('/portal/register', [PortalRegisterController::class, 'index'])
        ->name('portal.register');
    Route::post('/portal/register', [PortalRegisterController::class, 'register']);
    Route::post('/portal/logout', [PortalLoginController::class, 'destroy'])
        ->name('portal.logout');
});


/*--------------------------------------------------------------
# Gas Station Route
--------------------------------------------------------------*/
Route::get('/gasStation', [FleetController::class, 'gasStationIndex'])
    ->name('gasStation');
Route::post('/gasStation/verify', [FleetController::class, 'gasStationVerify'])
    ->name('gasStation.verify');

/*--------------------------------------------------------------
# Legal Route
--------------------------------------------------------------*/
Route::view('/privacy-policy', 'pages.legal.privacyPolicy')
    ->name('privacy-policy');
Route::view('/terms-and-conditions', 'pages.legal.termsAndConditions')
    ->name('terms-and-conditions');

/*--------------------------------------------------------------
# Geocode Route
--------------------------------------------------------------*/
Route::get('/geocode/autocomplete/{query}', [GeocodeController::class, 'getAutocomplete']);
