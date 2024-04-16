<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\ApiKeyFactory;
use Database\Factories\ValueObjects\UnixTimestampFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class BotFactory
{
    private ?Uuid $uuid = null;
    private ?ApiKey $apiKey = null;
    private ?Username $username = null;
    private ?UnixTimestamp $registeredAt = null;

    public function __construct(
        private readonly ApiKeyFactory $apiKeyFactory,
        private readonly UsernameFactory $usernameFactory,
        private readonly UnixTimestampFactory $unixTimestampFactory,
    ) {}

    public function uuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

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

    public function registeredAt(UnixTimestamp $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    private function reset(): void
    {
        $this->uuid = null;
        $this->apiKey = null;
        $this->username = null;
        $this->registeredAt = null;
    }

    public function create(): Bot
    {
        $bot = new Bot(
            botId: $this->uuid ?? Uuid::random(),
            apiKey: $this->apiKey ?? $this->apiKeyFactory->create(),
            username: $this->username ?? $this->usernameFactory->create(),
            registeredAt: $this->registeredAt ?? $this->unixTimestampFactory->create(),
        );
        $this->reset();

        return $bot;
    }
}
