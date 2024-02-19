<?php

// Start Oxygen:Devices Routes
Route::group([
	'prefix' => 'manage', 'as' => 'manage.',
	'middleware' => ['auth', 'auth.acl:roles[super-admins|admins|developers]'],
	], function () {

		Route::resource('devices', '\App\Http\Controllers\Manage\ManageDevicesController')
		 ->only('index', 'show', 'destroy');
	});
// End Devices Routes
