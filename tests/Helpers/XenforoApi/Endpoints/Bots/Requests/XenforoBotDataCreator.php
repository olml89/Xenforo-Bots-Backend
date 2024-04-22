<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots\Requests;

use Database\Factories\BotFactory;
use Database\Factories\ValueObjects\UnixTimestampFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotData;

final class XenforoBotDataCreator
{
    private ?string $bot_id = null;
    private ?string $api_key = null;
    private ?int $created_at = null;
    private ?Bot $bot = null;

    public function __construct(
        private readonly BotFactory $botFactory,
        private readonly UnixTimestampFactory $unixTimestampFactory,
    ) {}

    public function botId(string $bot_id): self
    {
        $this->bot_id = $bot_id;

        return $this;
    }

    public function apiKey(string $api_key): self
    {
        $this->api_key = $api_key;

        return $this;
    }

    public function createdAt(int $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function bot(Bot $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    private function reset(): void
    {
        $this->bot_id = null;
        $this->api_key = null;
        $this->created_at = null;
        $this->bot = null;
    }

    public function create(): XenforoBotData
    {
        $this->bot ??= $this->botFactory->create();

        $xenforoBotData = new XenforoBotData(
            bot_id: $this->bot_id ?? (string)$this->bot->botId(),
            api_key: $this->api_key ?? (string)$this->bot->apiKey(),
            created_at: $this->created_at ?? $this->unixTimestampFactory->create()->timestamp()
        );
        $this->reset();

        return $xenforoBotData;
    }
}
