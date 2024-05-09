<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final readonly class BotFinder
{
    public function __construct(
        private BotRepository $botRepository,
    ) {}

    /**
     * @throws BotNotFoundException
     */
    public function find(Uuid $botId): Bot
    {
        return $this->botRepository->get($botId) ?? throw BotNotFoundException::botId($botId);
    }

    /**
     * @throws BotNotFoundException
     */
    public function findBy(BotSpecification $specification): Bot
    {
        return $this
            ->botRepository
            ->getOneBy($specification) ?? throw BotNotFoundException::specification($specification);
    }
}
