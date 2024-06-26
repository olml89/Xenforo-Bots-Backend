<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

final readonly class BotActivator
{
    public function __construct(
        private RemoteBotActivator $remoteBotActivator,
        private BotRepository $botRepository,
    ) {}

    /**
     * @throws BotActivationException
     * @throws BotStorageException
     */
    public function activate(Bot $bot): void
    {
        $this->remoteBotActivator->activate($bot);
        $bot->activate();
        $this->botRepository->save($bot);
    }
}
