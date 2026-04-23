<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn;

use Illuminate\Support\Facades\App;
use Perseid\LaravelCdn\Contracts\ProviderFactoryInterface;
use Perseid\LaravelCdn\Exceptions\MissingConfigurationException;
use Perseid\LaravelCdn\Exceptions\UnsupportedProviderException;

class ProviderFactory implements ProviderFactoryInterface
{
    const string DRIVERS_NAMESPACE = 'Perseid\\LaravelCdn\\Providers\\';

    public function create($configurations = [])
    {
        // get the default provider name
        $provider = $configurations['default'] ?? null;

        if (! $provider) {
            throw new MissingConfigurationException('Missing Configurations: Default Provider');
        }

        // prepare the full driver class name
        $driver_class = self::DRIVERS_NAMESPACE.ucwords((string) $provider).'Provider';

        if (! class_exists($driver_class)) {
            throw new UnsupportedProviderException(sprintf('CDN provider (%s) is not supported', $provider));
        }

        // initialize the driver object and initialize it with the configurations
        return App::make($driver_class)->init($configurations);
    }
}
