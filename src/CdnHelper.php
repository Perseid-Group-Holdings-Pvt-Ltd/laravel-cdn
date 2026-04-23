<?php

namespace Perseid\LaravelCdn;

use Illuminate\Contracts\Config\Repository;
use Perseid\LaravelCdn\Contracts\CdnHelperInterface;
use Perseid\LaravelCdn\Exceptions\MissingConfigurationException;
use Perseid\LaravelCdn\Exceptions\MissingConfigurationFileException;

/**
 * Class CdnHelper
 * Helper class containing shared functions.
 *
 * @category General Helper
 */
class CdnHelper implements CdnHelperInterface
{
    public function __construct(protected Repository $configurations) {}

    public function getConfigurations()
    {
        $configurations = $this->configurations->get('cdn');

        if (! $configurations) {
            throw new MissingConfigurationFileException("CDN 'config file' (cdn.php) not found");
        }

        return $configurations;
    }

    public function validate($configuration, $required): void
    {
        // search for any null or empty field to throw an exception
        $missing = '';
        foreach ($configuration as $key => $value) {
            if (in_array($key, $required) &&
                (empty($value) || $value == null || $value == '')
            ) {
                $missing .= ' '.$key;
            }
        }

        if ($missing !== '' && $missing !== '0') {
            throw new MissingConfigurationException('Missed Configuration:'.$missing);
        }
    }

    public function parseUrl($url): array|false
    {
        return parse_url((string) $url);
    }

    /**
     * check if a string starts with a string.
     */
    public function startsWith($with, $str): bool
    {
        return str_starts_with((string) $str, (string) $with);
    }

    /**
     * remove any extra slashes '/' from the path.
     */
    public function cleanPath($path): string
    {
        return rtrim(ltrim((string) $path, '/'), '/');
    }
}
