<?php

namespace Perseid\LaravelCdn;

use Perseid\LaravelCdn\Contracts\AssetInterface;
use Perseid\LaravelCdn\Contracts\FinderInterface;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class Finder.
 *
 * @category Finder Helper
 */
class Finder extends SymfonyFinder implements FinderInterface
{
    public function __construct(protected \Symfony\Component\Console\Output\ConsoleOutput $console)
    {
        parent::__construct();
    }

    /**
     * return a collection of arrays of assets paths found
     * in the included directories, except all ignored
     * (directories, patterns, extensions and files).
     *
     *
     * @return Collection
     */
    public function read(AssetInterface $asset_holder)
    {
        /*
         * add the included directories and files
         */
        $this->includeThis($asset_holder);
        /*
         * exclude the ignored directories and files
         */
        $this->excludeThis($asset_holder);

        // user terminal message
        $this->console->writeln('<fg=yellow>Files to upload:</fg=yellow>');

        // get all allowed 'for upload' files objects (assets) and store them in an array
        $assets = [];
        foreach ($this->files() as $file) {
            // user terminal message
            $this->console->writeln('<fg=cyan>Path: '.$file->getRealpath().'</fg=cyan>');

            $assets[] = $file;
        }

        return new Collection($assets);
    }

    /**
     * Add the included directories and files.
     */
    private function includeThis(AssetInterface $asset_holder): void
    {

        // include the included directories
        $this->in($asset_holder->getIncludedDirectories());

        // include files with this extensions
        foreach ($asset_holder->getIncludedExtensions() as $extension) {
            $this->name('*'.$extension);
        }

        // include patterns
        foreach ($asset_holder->getIncludedPatterns() as $pattern) {
            $this->name($pattern);
        }

        // exclude ignored directories
        $this->exclude($asset_holder->getExcludedDirectories());
    }

    /**
     * exclude the ignored directories and files.
     */
    private function excludeThis(AssetInterface $asset_holder): void
    {
        // add or ignore hidden directories
        $this->ignoreDotFiles($asset_holder->getExcludeHidden());

        // exclude ignored files
        foreach ($asset_holder->getExcludedFiles() as $name) {
            $this->notName($name);
        }

        // exclude files (if exist) with this extensions
        $excluded_extensions = $asset_holder->getExcludedExtensions();
        if (! empty($excluded_extensions)) {
            foreach ($asset_holder->getExcludedExtensions() as $extension) {
                $this->notName('*'.$extension);
            }
        }

        // exclude the regex pattern
        foreach ($asset_holder->getExcludedPatterns() as $pattern) {
            $this->notName($pattern);
        }
    }
}

