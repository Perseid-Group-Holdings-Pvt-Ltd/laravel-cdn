<?php

declare(strict_types=1);

namespace Perseid\Contracts;

interface ProviderFactoryInterface
{
    public function create($configurations);
}
