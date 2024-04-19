<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\ApiKeyGenerator\ApikeyGeneratorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DoctrineServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Providers\AppServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\UrlValidator\UrlValidatorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumerServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo\XenforoSubscriptionServiceProvider;

return [

    /*
     * Package Service Providers...
     */
    DoctrineServiceProvider::class,

    /*
     * Default Application Service Providers...
     */
    AppServiceProvider::class,

    /*
    * Application Service Providers...
    */
    ApikeyGeneratorServiceProvider::class,
    UrlValidatorServiceProvider::class,
    XenforoApiConsumerServiceProvider::class,
    XenforoBotServiceProvider::class,
    XenforoSubscriptionServiceProvider::class,

];
