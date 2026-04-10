<?php

declare(strict_types=1);

namespace Perseid\Contracts;

interface CdnInterface
{
    public function push();

    public function emptyBucket();
}
