<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotAuthenticator;

use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Bot\Domain\BotAuthenticator;
use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Auth\RequestData as AuthRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApiException;

final class XenforoBotAuthenticator implements BotAuthenticator
{
    public function __construct(
        public readonly XenforoApi $xenforoApi,
        private readonly UuidManager $uuidManager,
        private readonly Hasher $hasher,
    ) {}

    /**
     * @throws BotCreationException
     */
    public function authenticate(Username $username, string $password): Bot
    {
        try {
            $authRequestData = new AuthRequestData(
                login: (string)$username,
                password: $password,
            );
            $userResponseData = $this->xenforoApi->postAuth($authRequestData);

            return new Bot(
                id: $this->uuidManager->random(),
                userId: new AutoId($userResponseData->user_id),
                name: $username,
                password: new Password($password, $this->hasher),
                registeredAt: UnixTimestamp::toDateTimeImmutable($userResponseData->register_date),
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new BotCreationException($e->getMessage(), $e);
        }
    }
}
