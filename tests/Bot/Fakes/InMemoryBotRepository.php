<?php declare(strict_types=1);

namespace Tests\Bot\Fakes;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use WeakMap;

final class InMemoryBotRepository implements BotRepository
{
    /**
     * @var WeakMap<Uuid, Bot>
     */
    private WeakMap $bots;

    public function __construct(Bot ...$bots)
    {
        $this->bots = new WeakMap();

        foreach ($bots as $bot) {
            $this->bots[$bot->botId()] = $bot;
        }
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
        return $this->bots[$botId] ?? null;
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
        $this->bots[$bot->botId()] = $bot;
    }
}
