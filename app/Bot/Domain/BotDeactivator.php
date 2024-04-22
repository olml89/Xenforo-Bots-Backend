<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

final readonly class BotDeactivator
{
    public function __construct(
        private RemoteBotDeactivator $remoteBotDeactivator,
    ) {}

    /**
     * @throws BotDeactivationException
     */
    public function deactivate(Bot $bot): void
    {
        $this->remoteBotDeactivator->deactivate($bot);

        $bot->deactivate();
    }
}
