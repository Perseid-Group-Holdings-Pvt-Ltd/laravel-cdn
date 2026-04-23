<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Contracts;

interface CdnHelperInterface
{
    public function getConfigurations();

    public function validate($configuration, $required);

    public function parseUrl($url);

    public function startsWith(string $haystack, string $needle);

    public function cleanPath($path);
}
