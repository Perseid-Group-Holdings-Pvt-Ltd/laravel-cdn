<?php

namespace Perseid\LaravelCdn\Validators;

use Perseid\LaravelCdn\Exceptions\MissingConfigurationException;
use Perseid\LaravelCdn\Validators\Contracts\ProviderValidatorInterface;

class ProviderValidator extends Validator implements ProviderValidatorInterface
{
    /**
     * Checks for any required configuration is missed.
     *
     *
     * @throws MissingConfigurationException
     */
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
}
