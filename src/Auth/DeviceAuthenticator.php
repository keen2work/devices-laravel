<?php

namespace EMedia\Devices\Auth;

use App\User;
use EMedia\Devices\Entities\Devices\Device;
use EMedia\Devices\Entities\Devices\DevicesRepository;
use ElegantMedia\OxygenFoundation\Exceptions\UserNotFoundException;
use EMedia\Devices\Exceptions\DeviceNotFoundException;

class DeviceAuthenticator
{

	/**
	 * @var DevicesRepository
	 */
	private $devicesRepo;

	public function __construct(DevicesRepository $devicesRepo)
	{
		$this->devicesRepo = $devicesRepo;
	}


	/**
	 *
	 * Return a valid access token for a given user ID
	 *
	 * @param $userId
	 *
	 * @return null
	 */
	public static function getAnAccessTokenForUserId($userId)
	{
		$device = Device::where('user_id', $userId)->active()->first();

		if ($device) {
			return $device->access_token;
		}

		return null;
	}

	/**
	 *
	 * Returns a user by a given access token
	 *
	 * @return null|User
	 */
	public static function getUserByAccessToken($throwNotFoundException = true)
	{
		$accessToken = request()->header('X-Access-Token');

		if (empty($accessToken)) {
			throw new \InvalidArgumentException('Invalid access token');
		}

		/** @var Device $device */
		$device = self::findDeviceByToken($accessToken);

		if (!$device) {
			if ($throwNotFoundException) {
				throw new DeviceNotFoundException();
			}
			return null;
		}

		if (empty($device->user)) {
			throw new UserNotFoundException("User not found for device ID {$device->id}");
		}

		return $device->user;
	}

	/**
	 *
	 * Get an access token
	 *
	 * @param $deviceId
	 * @param $userId
	 *
	 * @return int
	 */
	public function getTokenByDeviceByUser($deviceId, $userId)
	{
		$accessToken = Device::where('device_id', $deviceId)
					   ->active()
					   ->where('user_id', $userId)
					   ->first();

		return (empty($accessToken))? null: $accessToken->access_token;
	}


	public function setToken($deviceId, $deviceType, $devicePushToken, $userId)
	{
		$device = new Device([
			'device_id' => $deviceId,
			'device_type' => $deviceType,
			'device_push_token' => $devicePushToken,
			'user_id' => $userId
		]);

		return $device->token;
	}


	/**
	 *
	 * Find a device by access token
	 *
	 * @param $accessToken
	 *
	 * @return mixed
	 */
	public static function findDeviceByToken($accessToken)
	{
		return Device::where('access_token', $accessToken)->active()->first();
	}

	/**
	 *
	 * Validate to see if a token exists
	 *
	 * @param $accessToken
	 *
	 * @return bool
	 */
	public static function validateToken($accessToken)
	{
		$accessToken = Device::where('access_token', $accessToken)->active()->first();

		return ($accessToken)? true: false;
	}


	public function deleteByToken($deviceId, $accessToken = null)
	{
		if (!$accessToken) {
			//delete all the tokens associated with device
			$this->devicesRepo->deleteByToken($accessToken);
		} else {
			$this->devicesRepo->deleteByDeviceId($deviceId);
		}

		return true;
	}

	/**
	 *
	 * Clear access tokens by a user ID
	 *
	 * @param $userId
	 */
	public static function clearAllAccessTokensByUserId($userId)
	{
		Device::where('user_id', $userId)->update([
			'access_token' => null,
			'access_token_expires_at' => null,
		]);
	}

	/**
	 *
	 * Clear an access token
	 *
	 * @param $accessToken
	 */
	public static function clearAccessToken($accessToken)
	{
		Device::where('access_token', $accessToken)->update([
			'access_token' => null,
			'access_token_expires_at' => null,
		]);
	}
}
