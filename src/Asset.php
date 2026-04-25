<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn;

use Illuminate\Support\Collection;
use Perseid\LaravelCdn\Contracts\AssetInterface;

/**
 * Class Asset used to parse and hold all assets and
 * paths related data and configurations.
 */
class Asset implements AssetInterface
{
    /*
     * Allowed assets for upload (found in included_directories)
     */
    public Collection $assets;

    /**
     * default [include] configurations.
     */
    protected array $default_include = [
        'directories' => ['public'],
        'extensions' => [],
        'patterns' => [],
    ];

    /**
     * default [exclude] configurations.
     */
    protected array $default_exclude = [
        'directories' => [],
        'files' => [],
        'extensions' => [],
        'patterns' => [],
        'hidden' => true,
    ];

    protected array $included_directories;

    protected array $included_extensions;

    protected array $included_patterns;

    protected array $excluded_directories;

    protected array $excluded_files;

    protected array $excluded_extensions;

    protected array $excluded_patterns;

    protected bool $exclude_hidden;

    /**
     * build an Asset object that contains the assets related configurations.
     */
    public function init($configurations = []): static
    {
        $this->parseAndFillConfiguration($configurations);

        $this->included_directories = $this->default_include['directories'];
        $this->included_extensions = $this->default_include['extensions'];
        $this->included_patterns = $this->default_include['patterns'];

        $this->excluded_directories = $this->default_exclude['directories'];
        $this->excluded_files = $this->default_exclude['files'];
        $this->excluded_extensions = $this->default_exclude['extensions'];
        $this->excluded_patterns = $this->default_exclude['patterns'];
        $this->exclude_hidden = $this->default_exclude['hidden'];

        return $this;
    }

    public function getIncludedDirectories(): array
    {
        return $this->included_directories;
    }

    public function getIncludedExtensions(): array
    {
        return $this->included_extensions;
    }

    public function getIncludedPatterns(): array
    {
        return $this->included_patterns;
    }

    public function getExcludedDirectories(): array
    {
        return $this->excluded_directories;
    }

    public function getExcludedFiles(): array
    {
        return $this->excluded_files;
    }

    public function getExcludedExtensions(): array
    {
        return $this->excluded_extensions;
    }

    public function getExcludedPatterns(): array
    {
        return $this->excluded_patterns;
    }

    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function setAssets($assets): void
    {
        $this->assets = $assets;
    }

    public function getExcludeHidden(): bool
    {
        return $this->exclude_hidden;
    }

    /**
     * Check if the config file has any missed attribute, and if any attribute
     * is missed will be overridden by a default attribute defined in this class.
     */
    private function parseAndFillConfiguration(array $configurations): void
    {
        $this->default_include = isset($configurations['include']) ?
            array_merge($this->default_include, $configurations['include']) : $this->default_include;

        $this->default_exclude = isset($configurations['exclude']) ?
            array_merge($this->default_exclude, $configurations['exclude']) : $this->default_exclude;
    }
}
