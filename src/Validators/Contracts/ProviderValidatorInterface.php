<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Validators\Contracts;

interface ProviderValidatorInterface
{
    public function validate($configuration, $required);
}
