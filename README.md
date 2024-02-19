# Device-Authentication Handling Package for Laravel

This adds device authentication handling capability on a laravel backend.

## Version Compatibility

| Laravel Version | Package Version | Branch       |
|-----------------|-----------------|--------------|
| v10             | 5.x             | 5.x          |
| v9              | 4.x             | 4.x          |
| v8              | 3.x             | version/v3.x |
| v7              | 2.x             | version/v2.x |
| v6              | 1.1.x           |              | 
| v5.8            | 1.0.x           |              | 

See [change log for change history](CHANGELOG.md) and compatibility with past versions.

## Installation Instructions

Add the private repositories in your `composer.json`.

```
    "repositories": [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:elegantmedia/devices-laravel.git"
        }
    ],
```

Add the repository to the application
```
composer require emedia/devices-laravel
```

## Setup

Install the package.

```
php artisan oxygen:devices:install
```

Then migrate to create the tables and seed the database.

``` bash
php artisan migrate
php artisan db:seed
```

## Optional Customisation Steps

```
// Seed the database
php artisan db:seed --class="Database\Seeders\OxygenExtensions\AutoSeed\DevicesTableSeeder"

// Publish views
php artisan vendor:publish --provider="EMedia\Devices\DevicesServiceProvider" --tag=views --force
```


## Usage

After installation, you can get, set, validate and discard the token at any point of the application.

```
use EMedia\Devices\Auth\DeviceAuthenticator;

DeviceAuthenticator::getTokenByDeviceByUser($deviceId, $userId);
DeviceAuthenticator::setAccessToken($deviceId);
DeviceAuthenticator::validateAccessToken($accessToken);
DeviceAuthenticator::discardAccessToken($deviceId, $accessToken = null);
```

## Middleware

Use device authentication to validate token as a middleware, add below line to `Http\Kernel.php`. This will check for a valid `x-access-token` in header, and if it's present, let it through.

```
'auth.device' => '\EMedia\Devices\Middleware\AuthorizeDeviceMiddleware::class,'
```

## Limitations

- This package will only handle device access tokens. It will not check or validate API keys or API requests.

## Contributing

- Found a bug? Report as an issue and if you can, submit a pull request.
- Please see [CONTRIBUTING](CONTRIBUTING.md) and for details.

Copyright (c) Elegant Media.
