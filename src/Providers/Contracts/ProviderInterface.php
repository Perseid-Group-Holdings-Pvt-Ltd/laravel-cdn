<?php

declare(strict_types=1);

namespace Perseid\LaravelCdn\Providers\Contracts;

interface ProviderInterface
{
    public function init($configurations);

    public function upload($assets);

    public function emptyBucket();

    public function urlGenerator($path);

    public function getUrl();

    public function getCloudFront();

    public function getCloudFrontUrl();

    public function getBucket();

    public function setS3Client($s3_client);
}
