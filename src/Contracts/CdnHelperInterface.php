<?php

declare(strict_types=1);

namespace Perseid\Contracts;

interface CdnHelperInterface
{
    public function getConfigurations();

    public function validate($configuration, $required);

    public function parseUrl($url);

    public function startsWith($haystack, $needle);

    public function cleanPath($path);
}
