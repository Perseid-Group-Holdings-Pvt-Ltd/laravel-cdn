<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn;

use Perseid\LaravelCdn\Contracts\CdnFacadeInterface;
use Perseid\LaravelCdn\Contracts\CdnHelperInterface;
use Perseid\LaravelCdn\Contracts\ProviderFactoryInterface;
use Perseid\LaravelCdn\Exceptions\EmptyPathException;
use Perseid\LaravelCdn\Providers\Contracts\ProviderInterface;
use Perseid\LaravelCdn\Validators\CdnFacadeValidator;

class CdnFacade implements CdnFacadeInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * instance of the default provider object.
     *
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * Calls the provider initializer.
     */
    public function __construct(
        protected ProviderFactoryInterface $provider_factory,
        protected CdnHelperInterface $helper,
        protected CdnFacadeValidator $cdn_facade_validator
    ) {
        $this->init();
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::asset('')}} to convert the path into
     * it's CDN url.
     *
     *
     * @throws EmptyPathException
     */
    public function asset($path): mixed
    {
        // if asset always append the public/ dir to the path (since the user should not add public/ to asset)
        return $this->generateUrl($path, $this->configurations['providers']['aws']['s3']['upload_folder'].'public/');
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::mix('')}} to convert the Laravel 5.4 webpack mix
     * generated file path into it's CDN url.
     *
     *
     * @return mixed
     *
     * @throws EmptyPathException, \InvalidArgumentException
     */
    public function mix(string $path)
    {
        static $manifest = null;
        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('mix-manifest.json')), true);
        }

        if (isset($manifest['/'.$path])) {
            return $this->generateUrl($manifest['/'.$path], 'public/');
        }

        if (isset($manifest[$path])) {
            return $this->generateUrl($manifest[$path], 'public/');
        }

        throw new \InvalidArgumentException(sprintf('File %s not defined in asset manifest.', $path));
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::elixir('')}} to convert the elixir generated file path into
     * it's CDN url.
     *
     *
     * @return mixed
     *
     * @throws EmptyPathException, \InvalidArgumentException
     */
    public function elixir($path)
    {
        static $manifest = null;
        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('build/rev-manifest.json')), true);
        }

        if (isset($manifest[$path])) {
            return $this->generateUrl('build/'.$manifest[$path], 'public/');
        }

        throw new \InvalidArgumentException(sprintf('File %s not defined in asset manifest.', $path));
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::vite('')}} to convert the vite generated file path into
     * it's CDN url.
     *
     *
     * @return mixed
     *
     * @throws EmptyPathException, \InvalidArgumentException
     */
    public function vite($path)
    {
        static $manifest = null;
        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        }

        if (isset($manifest[$path])) {
            return $this->generateUrl(
                'build/'.
                $manifest[$path]['file'],
                $this->configurations['providers']['aws']['s3']['upload_folder'].'public/');
        }

        throw new \InvalidArgumentException(sprintf('File %s not defined in asset manifest.', $path));
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::path('')}} to convert the path into
     * it's CDN url.
     *
     *
     * @return mixed
     *
     * @throws EmptyPathException
     */
    public function path(string $path)
    {
        return $this->generateUrl($path);
    }

    /**
     * Read the configuration file and pass it to the provider factory
     * to return an object of the default provider specified in the
     * config file.
     */
    private function init(): void
    {
        // return the configurations from the config file
        $this->configurations = $this->helper->getConfigurations();

        // return an instance of the corresponding Provider concrete according to the configuration
        $this->provider = $this->provider_factory->create($this->configurations);
    }

    /**
     * check if package is surpassed or not then
     * prepare the path before generating the url.
     *
     * @return mixed
     */
    private function generateUrl(string $path, string $prepend = '')
    {
        // if the package is surpassed, then return the same $path
        // to load the asset from the localhost
        if (isset($this->configurations['bypass']) && $this->configurations['bypass']) {
            return Request::root().'/'.$path;
        }

        if (! isset($path)) {
            throw new EmptyPathException('Path does not exist.');
        }

        // Add version number
        // $path = str_replace(
        //    "build",
        //    $this->configurations['providers']['aws']['s3']['version'],
        //    $path
        // );

        // remove slashes from begging and ending of the path
        // and append directories if needed
        $clean_path = $prepend.$this->helper->cleanPath($path);

        // call the provider specific url generator
        return $this->provider->urlGenerator($clean_path);
    }
}
