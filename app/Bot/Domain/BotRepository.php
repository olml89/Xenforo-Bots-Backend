<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

interface BotRepository
{
    /**
     * @return Bot[]
     */
    public function allSubscribed(): array;

    public function get(Uuid $botId): ?Bot;
    public function getByUsername(Username $username): ?Bot;

    /**
     * @throws BotStorageException
     */
    public function save(Bot $bot): void;
}
