<?php declare(strict_types=1);

namespace Tests\Bot\Fakes;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use WeakMap;

final class InMemoryBotRepository implements BotRepository
{
    /**
     * @var WeakMap<Uuid, Bot>
     */
    public WeakMap $bots;

    public function __construct(Bot ...$bots)
    {
        $this->bots = new WeakMap();

        foreach ($bots as $bot) {
            $this->bots[$bot->botId()] = $bot;
        }
    }

    /**
     * @return Bot[]
     */
    public function all(): array
    {
        $bots = [];

        foreach ($this->bots as $bot) {
            $bots[] = $bot;
        }

        return $bots;
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
        $this->bots[$bot->botId()] = $bot;
    }

    public function delete(Bot $bot): void
    {
        unset($this->bots[$bot->botId()]);
    }
}
