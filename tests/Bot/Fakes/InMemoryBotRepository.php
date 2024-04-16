<?php declare(strict_types=1);

namespace Tests\Bot\Fakes;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class InMemoryBotRepository implements BotRepository
{
    /**
     * @var Bot[]
     */
    private array $bots;

    public function __construct(Bot ...$bots)
    {
        $this->bots = $bots;
    }

    public function allSubscribed(): array
    {
        return array_map(
            fn (Bot $bot): bool => $bot->isSubscribed(),
            $this->bots
        );
    }

    public function get(Uuid $botId): ?Bot
    {
        foreach ($this->bots as $bot) {
            if ($bot->botId()->equals($botId)) {
                return $bot;
            }
        }

        return null;
    }

    public function getByUsername(Username $username): ?Bot
    {
        foreach ($this->bots as $bot) {
            if ($bot->username()->equals($username)) {
                return $bot;
            }
        }

        return null;
    }

    public function save(Bot $bot): void
    {
        $this->bots[] = $bot;
    }
}
