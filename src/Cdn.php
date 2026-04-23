<?php

namespace Perseid\LaravelCdn;

use Perseid\LaravelCdn\Contracts\AssetInterface;
use Perseid\LaravelCdn\Contracts\CdnHelperInterface;
use Perseid\LaravelCdn\Contracts\CdnInterface;
use Perseid\LaravelCdn\Contracts\FinderInterface;
use Perseid\LaravelCdn\Contracts\ProviderFactoryInterface;

/**
 * Class Cdn is the manager and the main class of this package.
 *
 * @category Main Class
 */
class Cdn implements CdnInterface
{
    /**
     * @internal param \app\CDN\Repository $configurations
     */
    public function __construct(
        /**
         * An instance of the finder class.
         *
         * @var Contracts\
         */
        protected FinderInterface $finder,
        /**
         * The object that will hold the assets configurations
         * and the paths of the assets.
         */
        protected AssetInterface $asset_holder,
        protected ProviderFactoryInterface $provider_factory,
        protected CdnHelperInterface $helper
    ) {}

    /**
     * Will be called from the PushCommand class on Fire().
     */
    public function push()
    {
        // return the configurations from the config file
        $configurations = $this->helper->getConfigurations();

        // Initialize an instance of the asset holder to read the configurations
        // then call the read(), to return all the allowed assets as a collection of files objects
        $assets = $this->finder->read($this->asset_holder->init($configurations));

        // store the returned assets in the instance of the asset holder as collection of paths
        $this->asset_holder->setAssets($assets);

        // return an instance of the corresponding Provider concrete according to the configuration
        $provider = $this->provider_factory->create($configurations);

        return $provider->upload($this->asset_holder->getAssets());
    }

    /**
     * Will be called from the EmptyCommand class on Fire().
     */
    public function emptyBucket()
    {
        // return the configurations from the config file
        $configurations = $this->helper->getConfigurations();

        // return an instance of the corresponding Provider concrete according to the configuration
        $provider = $this->provider_factory->create($configurations);

        return $provider->emptyBucket();
    }
}
