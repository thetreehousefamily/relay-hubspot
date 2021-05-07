<?php

namespace TheTreehouse\Relay\HubSpot;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotRelayException;
use TheTreehouse\Relay\RelayServiceProvider;

class HubSpotRelayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('relay-hubspot');
    }

    public function bootingPackage()
    {
        if (!$this->app->getProviders(RelayServiceProvider::class)) {

            throw HubSpotRelayException::dependentServiceNotLoaded();
        }
    }

    public function packageBooted()
    {
        Relay::registerProvider(HubSpotRelay::class);
    }
}
