<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Domain;

use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher;

final class BotFinder
{
    public function __construct(
        private readonly BotRepository $botRepository,
        private readonly Hasher $hasher,
    ) {}

    /**
     * @throws BotNotFoundException
     */
    public function find(Username $name, string $password): Bot
    {
        $bot = $this->botRepository->getByName($name) ?? throw BotNotFoundException::invalidName($name);

        if (!$bot->password()->check($password, $this->hasher)) {
            throw BotNotFoundException::invalidPassword();
        }

        return $bot;
    }
}
