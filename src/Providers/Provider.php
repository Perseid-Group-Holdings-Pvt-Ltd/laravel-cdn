<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Providers;

use Perseid\LaravelCdn\Providers\Contracts\ProviderInterface;

abstract class Provider implements ProviderInterface
{
    public $console;

    protected string $key;

    protected string $secret;

    protected string $region;

    protected string $version;

    protected string $url;

    abstract public function upload($assets);
}
