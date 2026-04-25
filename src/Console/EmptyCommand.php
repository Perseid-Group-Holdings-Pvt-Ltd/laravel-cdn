<?php

namespace Perseid\LaravelCdn\Console;

use Illuminate\Console\Command;

class EmptyCommand extends Command
{
    protected $signature = 'cdn:empty';

    protected $description = 'Empty the CDN storage';

    public function handle(): void
    {
        $this->info('Emptying the CDN storage...');

        app('Perseid\LaravelCdn\Contracts\CdnInterface')->emptyBucket();

        $this->info('CDN storage emptied successfully.');
    }
}
