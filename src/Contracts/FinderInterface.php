<?php

declare(strict_types=1);

namespace Perseid\Contracts;

interface FinderInterface
{
    public function read(AssetInterface $paths);
}
