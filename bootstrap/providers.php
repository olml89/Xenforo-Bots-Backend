<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DoctrineServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Providers\AppServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\UrlValidator\UrlValidatorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumerServiceProvider;

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
    UrlValidatorServiceProvider::class,
    XenforoApiConsumerServiceProvider::class,
    XenforoBotServiceProvider::class,

];
