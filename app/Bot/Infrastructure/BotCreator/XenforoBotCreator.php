<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotCreator;

use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Domain\BotCreator;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\UuidManager\RamseyUuidManager;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\User\RequestData as UserRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApiException;

final class XenforoBotCreator implements BotCreator
{
    public function __construct(
        private readonly XenforoApi $xenforoApi,
        private readonly Hasher $hasher,
        private readonly RamseyUuidManager $uuidManager,
    ) {}

    /**
     * @throws BotCreationException
     */
    public function create(string $name, string $password): Bot
    {
        try {
            $userRequestData = new UserRequestData(username: $name, password: $password);
            $userResponseData = $this->xenforoApi->postUser($userRequestData);

            return new Bot(
                id: $this->uuidManager->random(),
                userId: new AutoId($userResponseData->user_id),
                name: new Username($name),
                password: new Password($password, $this->hasher),
                registeredAt: UnixTimestamp::toDateTimeImmutable($userResponseData->register_date),
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new BotCreationException($e->getMessage(), $e);
        }
    }
}
