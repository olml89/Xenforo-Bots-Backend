<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Infrastructure\AnswerPublisher;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Answer\Domain\AnswerPublisherManager;
use olml89\XenforoBots\Answer\Domain\ContentType;

final class AnswerPublisherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AnswerPublisherManager::class, function(Application $app): AnswerPublisherManager {
            $answerPublisherManager = new AnswerPublisherManager();

            return $answerPublisherManager
                ->add(ContentType::POST, $app->get(XenforoPostPublisher::class));
        });
    }
}
