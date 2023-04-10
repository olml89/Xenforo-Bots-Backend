<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Domain;

interface BotRepository
{
    /**
     * @return Bot[]
     */
    public function allSubscribed(): array;

    public function getByName(Username $name): ?Bot;

    /**
     * @throws BotStorageException
     */
    public function save(Bot $bot): void;
}
