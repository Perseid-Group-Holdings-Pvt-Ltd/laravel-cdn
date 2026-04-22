<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Contracts;

interface FinderInterface
{
    public function read(AssetInterface $paths);
}
