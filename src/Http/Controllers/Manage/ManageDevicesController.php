<?php


namespace EMedia\Devices\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanCRUD;
use EMedia\Devices\Entities\Devices\DevicesRepository;

class ManageDevicesController extends Controller
{

	use CanCRUD;

	public function __construct(DevicesRepository $repo)
	{
		$this->repo = $repo;

		$this->resourceEntityName = 'Devices';

		$this->viewsVendorName = 'devices';

		$this->resourcePrefix = 'manage';

		$this->isDestroyAllowed = true;
	}

	public function show($id)
	{
		$device = $this->repo->find($id);

		if (!$device) {
			return back()->with('error', "Requested ID $id is not found");
		}

		$data = [
			'entity' => $device,
			'pageTitle' => 'Devices Details',
		];

		return view('devices::manage.show', $data);
	}
}
