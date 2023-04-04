<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Domain;

interface BotRepository
{
    /**
     * @throws BotStorageException
     */
    public function save(Bot $bot): void;
}
