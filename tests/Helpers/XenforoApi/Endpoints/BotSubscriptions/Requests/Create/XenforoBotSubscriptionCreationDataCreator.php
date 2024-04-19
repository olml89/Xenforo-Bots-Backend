<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create;

use Database\Factories\ValueObjects\ApiKeyFactory;
use Database\Factories\ValueObjects\UrlFactory;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo\XenforoBotSubscriptionCreationData;

final class XenforoBotSubscriptionCreationDataCreator
{
    private ?string $webhook = null;
    private ?string $platform_api_key = null;

    public function __construct(
        private readonly UrlFactory $urlFactory,
        private readonly ApiKeyFactory $apiKeyFactory,
    ) {}

    public function webhook(string $webhook): self
    {
        $this->webhook = $webhook;

        return $this;
    }

    public function platformApiKey(string $platform_api_key): self
    {
        $this->platform_api_key = $platform_api_key;

        return $this;
    }

    public function reset(): void
    {
        $this->webhook = null;
        $this->platform_api_key = null;
    }

    public function create(): XenforoBotSubscriptionCreationData
    {
        $xenforoBotSubscriptionCreationData = new XenforoBotSubscriptionCreationData(
            webhook: $this->webhook ?? (string)$this->urlFactory->create(),
            platform_api_key: $this->platform_api_key ?? (string)$this->apiKeyFactory->create()
        );
        $this->reset();

        return $xenforoBotSubscriptionCreationData;
    }
}
