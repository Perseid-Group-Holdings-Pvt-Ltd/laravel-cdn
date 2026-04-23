<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Facades;

use Illuminate\Support\Facades\Facade;

class CdnFacadeAccessor extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'CDN';
    }
}
