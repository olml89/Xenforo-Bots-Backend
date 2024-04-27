<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

interface BotRepository
{
    /**
     * @return Bot[]
     */
    public function all(): array;

    public function get(Uuid $botId): ?Bot;
    public function getByUsername(Username $username): ?Bot;

    /**
     * @throws BotStorageException
     */
    public function save(Bot $bot): void;

    /**
     * @throws BotStorageException
     */
    public function delete(Bot $bot): void;
}
