<?php

namespace EMedia\Devices\Entities\Devices;

use Carbon\Carbon;
use ElegantMedia\OxygenFoundation\Database\Eloquent\Traits\CreatesUniqueTokens;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Device extends Model
{

	use CreatesUniqueTokens;
	use Searchable;

	protected $defaultTokenExpiryDays = 90;

	protected $fillable = [
		'device_id',
		'device_type',
		'device_push_token',
		'user_id',
		'latest_ip_address',
	];

	public function getSearchableFields(): array
	{
		return [
			'device_id',
			'device_push_token',
			'latest_ip_address',
		];
	}

	protected $dates = [
		'access_token_expires_at',
	];

	public function getShowable()
	{
		return [
			'device_id',
			'device_type',
			'device_push_token',
			'user_id',
			'access_token',
		];
	}

	public function scopeActive($query)
	{
		return $query->where(function ($q) {
			$q->whereNull('access_token_expires_at');
			$q->orWhere('access_token_expires_at', '>', Carbon::now());
		});
	}

	public function user()
	{
		$userClass = config('auth.providers.users.model');

		if ($userClass) {
			return $this->belongsTo($userClass);
		}

		return null;
	}

	/**
	 *
	 * Force device type to be lower-case
	 *
	 * @param $value
	 */
	public function setDeviceTypeAttribute($value)
	{
		$this->attributes['device_type'] = strtolower($value);
	}

	/**
	 *
	 * Refresh the current access token
	 *
	 */
	public function refreshAccessToken()
	{
		$this->attributes['access_token'] = self::newUniqueToken('access_token');
		$this->attributes['access_token_expires_at'] = Carbon::now()->addDays($this->getDefaultTokenExpiryDays());
		$this->save();
	}

	/**
	 *
	 * Reset device access token
	 *
	 */
	public function resetAccessToken()
	{
		$this->attributes['access_token'] = null;
		$this->attributes['access_token_expires_at'] = null;
		$this->save();
	}

	/**
	 *  Setup model event hooks
	 */
	public static function boot()
	{
		parent::boot();

		self::creating(function ($model) {
			$model->access_token = self::newUniqueToken('access_token');
			$model->access_token_expires_at = Carbon::now()->addDays($model->getDefaultTokenExpiryDays());
		});
	}

	/**
	 * @return int
	 */
	public function getDefaultTokenExpiryDays(): int
	{
		return $this->defaultTokenExpiryDays;
	}

	/**
	 * @param int $defaultTokenExpiryDays
	 */
	public function setDefaultTokenExpiryDays(int $defaultTokenExpiryDays)
	{
		$this->defaultTokenExpiryDays = $defaultTokenExpiryDays;

		return $this;
	}
}
