<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface RemoteBotDeactivator
{
    /**
     * @throws BotDeactivationException
     */
    public function deactivate(Bot $bot): void;
}
