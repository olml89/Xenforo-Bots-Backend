<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\Bot;

interface BotRepository
{
    /**
     * @throws BotStorageException
     */
    public function save(Bot $bot): void;
}
