<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface BotDeactivator
{
    /**
     * @throws BotDeactivationException
     */
    public function deactivate(Bot $bot): void;
}
