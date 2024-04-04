<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Reply\Infrastructure\ReplyPublisher;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Reply\Domain\ReplyPublisherManager;
use olml89\XenforoBotsBackend\Reply\Domain\ContentType;

final class ReplyPublisherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ReplyPublisherManager::class, function(Application $app): ReplyPublisherManager {
            $replyPublisherManager = new ReplyPublisherManager();

            return $replyPublisherManager
                ->add(ContentType::POST, $app->get(XenforoPostPublisher::class));
        });
    }
}
