<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Contracts;

interface ProviderFactoryInterface
{
    public function create($configurations);
}
