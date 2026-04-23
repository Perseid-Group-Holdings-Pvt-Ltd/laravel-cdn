<?php

namespace Perseid\LaravelCdn\Validators\Contracts;

interface ProviderValidatorInterface
{
    public function validate($configuration, $required);
}
