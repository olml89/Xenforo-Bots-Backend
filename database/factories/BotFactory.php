<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\ApiKeyFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class BotFactory
{
    private ?Uuid $botId = null;
    private ?ApiKey $apiKey = null;
    private ?Username $username = null;

    public function __construct(
        private readonly ApiKeyFactory $apiKeyFactory,
        private readonly UsernameFactory $usernameFactory,
    ) {}

    public function uuid(Uuid $botId): self
    {
        $this->botId = $botId;

        return $this;
    }

    public function apiKey(ApiKey $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function username(Username $username): self
    {
        $this->username = $username;

        return $this;
    }

    private function reset(): void
    {
        $this->botId = null;
        $this->apiKey = null;
        $this->username = null;
    }

    public function create(): Bot
    {
        $bot = new Bot(
            botId: $this->botId ?? Uuid::random(),
            apiKey: $this->apiKey ?? $this->apiKeyFactory->create(),
            username: $this->username ?? $this->usernameFactory->create()
        );
        $this->reset();

        return $bot;
    }
}
