<?php


namespace EMedia\Devices\Commands;

use ElegantMedia\OxygenFoundation\Console\Commands\ExtensionInstallCommand;
use EMedia\Devices\DevicesServiceProvider;

class OxygenDevicesInstallCommand extends ExtensionInstallCommand
{

	protected $signature = 'oxygen:devices:install';

	protected $description = 'Setup the Devices Extension';

	public function getExtensionServiceProvider(): string
	{
		return DevicesServiceProvider::class;
	}

	public function getExtensionDisplayName(): string
	{
		return 'Devices';
	}
}
