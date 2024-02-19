<?php


namespace EMedia\Devices;

use ElegantMedia\OxygenFoundation\Facades\Navigator;
use ElegantMedia\OxygenFoundation\Navigation\NavItem;
use EMedia\Devices\Auth\DeviceAuthenticator;
use EMedia\Devices\Commands\OxygenDevicesInstallCommand;
use Illuminate\Support\ServiceProvider;

class DevicesServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		if (app()->environment(['local', 'testing'])) {
			$this->commands(OxygenDevicesInstallCommand::class);
		}

		$this->app->singleton('emedia.devices.auth', DeviceAuthenticator::class);
	}

	public function boot()
	{
		// auto-publishing files
		$this->publishes([
			__DIR__ . '/../publish' => base_path(),
		], 'oxygen::auto-publish');

		// load default views
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'devices');

		// add vies for manual publishing
		$this->publishes([
			__DIR__ . '/../resources/views' => base_path('resources/views/vendor/devices'),
		], 'views');

		$this->setupNavItem();
	}

	protected function setupNavItem()
	{
		$navItem = new NavItem('Devices');
		$navItem->setResource('manage.devices.index')
			->setIconClass('fas fa-mobile-alt');

		Navigator::addItem($navItem, 'sidebar.manage');
	}
}
