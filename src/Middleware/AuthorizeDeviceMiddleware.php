<?php

namespace EMedia\Devices\Middleware;

use Closure;

use EMedia\Devices\Auth\DeviceAuthenticator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorizeDeviceMiddleware
{

	/**
	 * @param Request $request
	 * @param Closure $next
	 *
	 * @return JsonResponse|mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$tokenKey = 'x-access-token';

		if (!$request->hasHeader($tokenKey)) {
			return response()->apiErrorAccessDenied('A valid access token was not found in header.');
		}

		$accessToken = $request->header($tokenKey);

		if (!DeviceAuthenticator::validateToken($accessToken)) {
			return response()
				->apiErrorUnauthorized('Invalid access token given. Try logging out and logging-in again.');
		}

		return $next($request);
	}
}
