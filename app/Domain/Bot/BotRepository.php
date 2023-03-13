<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\Bot;

interface BotRepository
{
    public function save(Bot $bot): void;
}
