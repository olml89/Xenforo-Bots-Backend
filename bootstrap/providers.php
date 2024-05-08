<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Behaviour\Infrastructure\BehaviourPatternManagerServiceProvider;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\ApiKeyGenerator\ApikeyGeneratorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DoctrineServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Providers\AppServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\UrlValidator\UrlValidatorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\UuidGenerator\UuidGeneratorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumerServiceProvider;

return [

    /*
     * Default Application Service Providers...
     */
    AppServiceProvider::class,

    /*
    * Application Service Providers...
    */
    UuidGeneratorServiceProvider::class,
    ApikeyGeneratorServiceProvider::class,
    UrlValidatorServiceProvider::class,
    XenforoApiConsumerServiceProvider::class,
    XenforoBotServiceProvider::class,
    BehaviourPatternManagerServiceProvider::class,

    /*
     * Package Service Providers...
     */
    DoctrineServiceProvider::class,

];
