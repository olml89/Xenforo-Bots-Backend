<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionRemover;

use Illuminate\Foundation\Application;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiException;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRemovalException;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRemover;

final class XenforoSubscriptionRemover implements SubscriptionRemover
{
    private readonly Url $appUrl;

    public function __construct(
        private readonly XenforoApi $xenforoApi,
        Application $application,
    ) {
        $this->appUrl = $application[Url::class];
    }

    /**
     * @throws SubscriptionRemovalException
     */
    public function remove(Bot $bot): void
    {
        try {
            $this->xenforoApi->deleteSubscription(
                user_id: $bot->userId()->toInt(),
                webhook: $this->appUrl->urlencode(),
            );
        }
        catch (XenforoApiException $e) {
            throw new SubscriptionRemovalException($e->getMessage(), $e);
        }
    }
}
