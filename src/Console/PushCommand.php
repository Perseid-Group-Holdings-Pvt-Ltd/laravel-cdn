<?php

namespace Perseid\LaravelCdn\Console;

use Illuminate\Console\Command;

class PushCommand extends Command
{
    protected $signature = 'cdn:push';

    protected $description = 'Push assets to the CDN provider';

    public function handle(): void
    {
        $this->info('Pushing assets to the CDN provider...');

        app('Perseid\LaravelCdn\Contracts\CdnInterface')->push();

        $this->info('Assets pushed successfully!');
    }
}
