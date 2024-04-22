<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface BotActivator
{
    /**
     * @throws BotActivationException
     */
    public function activate(Bot $bot): void;
}
