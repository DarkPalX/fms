<?php



######################################################################################
// Main Routes
	Route::get('/login', 'Auth\LoginController@login')->name('login');
	Route::post('/fms/checklogin', 'Auth\LoginController@checklogin');
	Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function () {
	Route::post('/change-passsword','MaintenanceController@change_password')->name('change-password');
	Route::get('/dashboard','DashboardController@dashboard')->name('dashboard');

// RQ Routes
	Route::post('/rq/verification','MaintenanceController@rq_verification')->name('verify-rq');
	Route::get('/fms/rq/index','MaintenanceController@rq_index')->name('rq.list');
	Route::get('/fms/rq/pending','MaintenanceController@rq_pending')->name('rq.pending');
	Route::get('/fms/unverified-rq','MaintenanceController@rq_unverified')->name('rq.unverified');
	Route::get('/search-rqno', 'SearchController@search_rqno')->name('search.rqno');
//

// Issuance Routes
	Route::get('/fms/manual-issuance','FuelController@manual')->name('manual-issuance');
	Route::post('/projectcode-fetch', 'FuelController@fetch_projectcodes')->name('projectcode.fetch');
	Route::post('/manual-issuance-store','FuelController@manual_issuance_store')->name('manual-issuance-store');

	Route::get('/fms/issuances','FuelController@index')->name('fuel.issuances');
	Route::get('/fms/pending-requests','FuelController@pending')->name('fuel.pending-request');
	Route::get('/fuel/issuance/process/{id}','FuelController@process')->name('process.issuance');
	Route::post('/fuel/issuance/complete','FuelController@complete')->name('complete.issuance');

	Route::post('/unpost-request','FuelController@unpost')->name('unpost-issuanced-request');
	Route::get('/edit-issued-request/{id}','FuelController@edit_issued')->name('edit-issued-request');
	Route::post('/update-issued-request','FuelController@update_issued')->name('update.issued-request');

	Route::post('/unlock-fuel-request','FuelController@unlock_request')->name('unlock-fuel-request');

	Route::get('/fuel/issuance/view/{id}','FuelController@view')->name('view.issuance');
	Route::get('/issuance/print/{id}','FuelController@print')->name('print.issuance');

	Route::get('/search-ref_no', 'SearchController@search_ref_no')->name('search.ref-no');
	Route::get('/search-issued-ref_no', 'SearchController@search_issued_ref_no')->name('search.issued-ref-no');
//

// Phase Code Routes
	Route::post('/phase-code/import', 'FileController@import_phase_code')->name('phasecode-import');
	Route::get('/phase/codes', 'MaintenanceController@phaseCode')->name('phase-code');
	Route::post('/dept-code/update','MaintenanceController@phasecode_update');
	Route::post('/dept-code/delete', 'MaintenanceController@deptCode_delete')->name('delete.dept-code');
//


// User Maintenance
	Route::get('/user/index','MaintenanceController@user')->name('users');
	Route::get('/user/create','MaintenanceController@user_create')->name('user.create');
	Route::post('/user-store','MaintenanceController@user_store')->name('user.store');
	Route::post('/user-delete','MaintenanceController@user_delete')->name('user-delete');
	Route::get('/user-edit/{id}','MaintenanceController@user_edit')->name('user.edit');
	Route::post('/user/update','MaintenanceController@user_update')->name('user.update');
	Route::get('/user/profile/{id}','MaintenanceController@user_profile'); // audit logs
//

// Fuel Maintenance Routes
	Route::get('/fuel/index','MaintenanceController@fuel_index')->name('fuel.index');
	Route::post('/fuel/delete','MaintenanceController@delete')->name('delete.fuel-type');
	Route::post('/fuel/store','MaintenanceController@fuel_store')->name('fuel-store');
	Route::post('/fuel/update','MaintenanceController@fuel_update')->name('fuel-update');
//

// Downtime Routes
	Route::get('/fms/downtime','MaintenanceController@downtime')->name('downtime.list');
	Route::view('/fms/downtime-create','maintenance.downtime-create')->name('downtime-create');
	Route::post('/fms/downtime-store','MaintenanceController@downtime_store')->name('downtime-store');
	Route::get('/fms/downtime-edit/{id}','MaintenanceController@downtime_edit')->name('downtime-edit');
	Route::post('/fms/downtime-update','MaintenanceController@downtime_update')->name('downtime-update');
//


// Vehicle Routes
	Route::get('/fms/vehicles','VehicleController@index')->name('vehicle.lists');
	Route::get('/vehicle/details/{id}','VehicleController@vehicleInfo')->name('fms.vehicle-summary');
	Route::get('/search-plateno', 'SearchController@search_plateno')->name('search.plateno');
	Route::post('/vehicle-import', 'FileController@vehicle_import')->name('vehicle-import');
	Route::post('/vehicle-details-update','VehicleController@update')->name('vehicle-details-update');
	Route::view('/create-vehicle','vehicle.create')->name('vehicle-create');
	Route::post('/vehicle-store','VehicleController@store')->name('vehicle-store');

	Route::post('/vehicle-delete','VehicleController@delete')->name('delete.vehicle');
//

// Report Routes
	Route::get('/report/issuance-status','ReportController@status')->name('report.issuance-status');
	Route::get('/report/issuance-department','ReportController@department')->name('report.issuance-department');
	Route::get('/report/issuance-vehicle','ReportController@vehicle')->name('report.issuance-vehicle');
	Route::get('/report/fuel-requests-issuances','ReportController@issuances')->name('report.fuel-issuances');
	
	Route::get('/report/unpost-request','ReportController@unpost')->name('report.unpost');

//

// Approval Routes
	Route::get('/fms/pending-approval','FuelController@pending_approval')->name('approval-pending.list');
	Route::get('/fms/approval/index','FuelController@approval_index')->name('approval.list');
	Route::get('/approval-request/{id}','FuelController@view_request')->name('view.request-details');
	Route::post('/approve-request','FuelController@approve')->name('approve-request');
	Route::post('disapprove-request','FuelController@disapprove')->name('disapprove-request');
//

// Project code maintenance
	Route::get('/project-codes', 'MaintenanceController@projectCode')->name('project-code');
	Route::post('/project-code/import', 'FileController@import_project_code')->name('projectcode-import');
	Route::post('/project-code/update','MaintenanceController@project_code_update')->name('projectcode-update');
	Route::post('/project-code/delete','MaintenanceController@projectcode_delete')->name('projectcode-delete');
//

	Route::get('/activity-logs','ActivityLogs@activities')->name('activity-logs');
//

// Export Routes
	// Issuance Status
	Route::get('/export/issuance-status/{status}','ExportController@issuance_status')->name('export.issuance-status');
	Route::get('/export/issuance-status/{status}/{startdate}/{enddate}','ExportController@issuance_status')->name('export.issuance-status-daterange');

	// Issuance Department
	Route::get('/export/issuance-department/{dept}/{status}','ExportController@issuance_department')->name('export.issuance-department');
	Route::get('/export/issuance-department/{dept}/{status}/{startdate}/{enddate}','ExportController@issuance_department')->name('export.issuance-department-daterange');

	// Issuance Vehicle
	Route::get('/export/issuance-vehicle/{vehicle}/{status}','ExportController@issuance_vehicle')->name('export.issuance-vehicle');
	Route::get('/export/issuance-vehicle/{vehicle}/{status}/{startdate}/{enddate}','ExportController@issuance_vehicle')->name('export.issuance-vehicle-daterange');

	// Issuances
	Route::get('/export/issuances/{startdate}/{enddate}','ExportController@issuances')->name('export.issuances-daterange');
//

	
//























// Chart Controller
	Route::get('/chart/monthly_served_fuel', 'ChartController@monthlyServedFuel');
	Route::get('/chart/fuel_consu', 'ChartController@fuelConsu');
	Route::get('/chart/monthly_fuel_consu_per_vehicle', 'ChartController@monthlyFuelConsuPerVehicle');
	Route::get('/chart/dept_budget_vs_consu', 'ChartController@deptBudgetVsConsu');
	Route::get('/chart/fuel_monthly_consu', 'ChartController@fuelMonthlyConsu');
	Route::get('/chart/fuel_issuance', 'ChartController@fuelMonthlyIssuance');
	Route::get('/list/fuel_avg_consu_diff','ChartController@avgFuelConsuDiff')->name('post');
	Route::get('/list/fuel_avg_consu_base_kml','ChartController@avgFuelConsuBaseKML')->name('post');
//


	Route::get('/avg_consu_per_vehicle','VehicleController@avgConsuPerVehicle')->name('post');

	
});
