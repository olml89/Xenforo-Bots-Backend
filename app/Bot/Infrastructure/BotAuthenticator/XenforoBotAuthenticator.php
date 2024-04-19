<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\BotAuthenticator;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotAuthenticator;
use olml89\XenforoBotsBackend\Bot\Domain\SubscriptionValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Auth\RequestData as AuthRequestData;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApi;

final class XenforoBotAuthenticator implements BotAuthenticator
{
    public function __construct(
        public readonly XenforoApi $xenforoApi,
        private readonly UuidManager $uuidManager,
        private readonly Hasher $hasher,
    ) {}

    /**
     * @throws SubscriptionValidationException
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
                registeredAt: UnixTimestamp::fromTimestamp($userResponseData->register_date),
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new SubscriptionValidationException($e->getMessage(), $e);
        }
    }
}
