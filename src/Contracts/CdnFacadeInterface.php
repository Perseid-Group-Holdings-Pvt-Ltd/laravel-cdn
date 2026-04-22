<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Contracts;

interface CdnFacadeInterface
{
    public function asset($path);
}
