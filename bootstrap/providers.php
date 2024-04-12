<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Bot\Infrastructure\BotAuthenticator\XenforoBotAuthenticatorServiceProvider;
use olml89\XenforoBotsBackend\Bot\Infrastructure\BotCreator\XenforoBotCreatorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DoctrineServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Providers\AppServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\UrlValidator\UrlValidatorServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumerServiceProvider;
use olml89\XenforoBotsBackend\Reply\Infrastructure\ReplyPublisher\ReplyPublisherServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionCreator\XenforoSubscriptionCreatorServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionRemover\XenforoSubscriptionRemoverServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionRetriever\XenforoSubscriptionRetrieverServiceProvider;

return [
    /*
     * Laravel Framework Service Providers...
     */
    /*
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    Illuminate\Encryption\EncryptionServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
    Illuminate\Notifications\NotificationServiceProvider::class,
    Illuminate\Pagination\PaginationServiceProvider::class,
    Illuminate\Pipeline\PipelineServiceProvider::class,
    Illuminate\Queue\QueueServiceProvider::class,
    Illuminate\Redis\RedisServiceProvider::class,
    Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
    Illuminate\Session\SessionServiceProvider::class,
    Illuminate\Translation\TranslationServiceProvider::class,
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,
    */

    /*
     * Default Application Service Providers...
     */
    AppServiceProvider::class,

    /*
    * Application Service Providers...
    */
    UrlValidatorServiceProvider::class,
    XenforoApiConsumerServiceProvider::class,
    XenforoBotCreatorServiceProvider::class,
    /*
    XenforoBotAuthenticatorServiceProvider::class,
    XenforoSubscriptionCreatorServiceProvider::class,
    XenforoSubscriptionRetrieverServiceProvider::class,
    XenforoSubscriptionRemoverServiceProvider::class,
    ReplyPublisherServiceProvider::class,
    */

    /*
     * Package Service Providers...
     */
    DoctrineServiceProvider::class,

];
