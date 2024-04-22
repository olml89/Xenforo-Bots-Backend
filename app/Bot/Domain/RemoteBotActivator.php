<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface RemoteBotActivator
{
    /**
     * @throws BotActivationException
     */
    public function activate(Bot $bot): void;
}
