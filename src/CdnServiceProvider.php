<?php

namespace Perseid\LaravelCdn;

use Illuminate\Support\ServiceProvider;
use Perseid\LaravelCdn\Console\EmptyCommand;
use Perseid\LaravelCdn\Console\PushCommand;

class CdnServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/cdn.php' => config_path('cdn.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                PushCommand::class,
                EmptyCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cdn.php', 'cdn'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Contracts\CdnInterface',
            'Perseid\LaravelCdn\Cdn'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Providers\Contracts\ProviderInterface',
            'Perseid\LaravelCdn\Providers\AwsS3Provider'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Contracts\AssetInterface',
            'Perseid\LaravelCdn\Asset'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Contracts\FinderInterface',
            'Perseid\LaravelCdn\Finder'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Contracts\ProviderFactoryInterface',
            'Perseid\LaravelCdn\ProviderFactory'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Contracts\CdnFacadeInterface',
            'Perseid\LaravelCdn\CdnFacade'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Contracts\CdnHelperInterface',
            'Perseid\LaravelCdn\CdnHelper'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Validators\Contracts\ProviderValidatorInterface',
            'Perseid\LaravelCdn\Validators\ProviderValidator'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Validators\Contracts\CdnFacadeValidatorInterface',
            'Perseid\LaravelCdn\Validators\CdnFacadeValidator'
        );

        $this->app->bind(
            'Perseid\LaravelCdn\Validators\Contracts\ValidatorInterface',
            'Perseid\LaravelCdn\Validators\Validator'
        );

        $this->app->singleton('Cdn', function ($app) {
            return $app->make('Perseid\LaravelCdn\Contracts\CdnFacadeInterface');
        });
    }
}
