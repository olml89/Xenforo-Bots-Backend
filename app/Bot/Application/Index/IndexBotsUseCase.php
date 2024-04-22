<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Index;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;

final readonly class IndexBotsUseCase
{
    public function __construct(
        private BotRepository $botRepository,
    ) {}

    /**
     * @return BotResult[]
     */
    public function index(): array
    {
        return array_map(
            fn (Bot $bot): BotResult => new BotResult($bot),
            $this->botRepository->all()
        );
    }
}
