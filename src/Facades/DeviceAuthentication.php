<?php

namespace EMedia\DeviceAuthentication\Facades;

use Illuminate\Support\Facades\Facade;

class DeviceAuthentication extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'emedia.devices.auth';
	}
}
