<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Create;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final readonly class CreateBotUseCase
{
    public function __construct(
        private BotRepository $botRepository,
        private BotCreator $botCreator,
    ) {}

    /**
     * @throws BotAlreadyExistsException
     * @throws BotValidationException
     * @throws BotStorageException
     */
    public function create(string $username, string $password): BotResult
    {
        try {
            $username = Username::create($username);
            $password = Password::create($password);

            if (!is_null($this->botRepository->getByUsername($username))) {
                throw BotAlreadyExistsException::username($username);
            }

            $bot = $this->botCreator->create($username, $password);
            $this->botRepository->save($bot);

            return new BotResult($bot);
        }
        catch (ValueObjectException $e) {
            throw new BotValidationException($e);
        }
    }
}
