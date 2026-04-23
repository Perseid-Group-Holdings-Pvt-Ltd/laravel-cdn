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
    protected Repository $configurations;

    public function __construct(Repository $configurations)
    {
        $this->configurations = $configurations;
    }

    public function getConfigurations()
    {
        $configurations = $this->configurations->get('cdn');

        if (! $configurations) {
            throw new MissingConfigurationFileException("CDN 'config file' (cdn.php) not found");
        }

        return $configurations;
    }

    public function validate($configuration, $required)
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

        if ($missing) {
            throw new MissingConfigurationException('Missed Configuration:'.$missing);
        }
    }

    public function parseUrl($url)
    {
        return parse_url($url);
    }

    /**
     * check if a string starts with a string.
     *
     *
     * @return bool
     */
    public function startsWith($with, $str)
    {
        return substr($str, 0, strlen($with)) === $with;
    }

    /**
     * remove any extra slashes '/' from the path.
     *
     *
     * @return string
     */
    public function cleanPath($path)
    {
        return rtrim(ltrim($path, '/'), '/');
    }
}
