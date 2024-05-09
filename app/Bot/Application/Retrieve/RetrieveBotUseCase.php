<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Retrieve;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\EqualsUsernameSpecification;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\InvalidUsernameException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;

final readonly class RetrieveBotUseCase
{
    public function __construct(
        private BotFinder $botFinder,
    ) {}

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     */
    public function retrieve(string $username): BotResult
    {
        try {
            $username = Username::create($username);

            $bot = $this
                ->botFinder
                ->findBy(new EqualsUsernameSpecification($username));

            return new BotResult($bot);
        }
        catch (InvalidUsernameException $e) {
            throw BotValidationException::fromException($e);
        }
    }
}
