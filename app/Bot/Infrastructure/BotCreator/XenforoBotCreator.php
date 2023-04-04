<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotCreator;

use GuzzleHttp\Exception\GuzzleException;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Domain\BotCreator;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\UuidManager\UuidManager;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiConsumer;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiErrorResponseData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\User\Create\CreateUserRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\User\Create\CreateUserResponseData;

final class XenforoBotCreator implements BotCreator
{
    public function __construct(
        private readonly ApiConsumer $apiConsumer,
        private readonly Hasher $hasher,
        private readonly UuidManager $uuidManager,
    ) {}

    public function create(string $name, string $password): Bot
    {
        try {
            $response = $this->apiConsumer->post('/users', new CreateUserRequestData($name, $password));

            if ($response->getStatusCode() !== 200) {
                $apiErrorResponseData = ApiErrorResponseData::fromResponse($response);
                throw new BotCreationException($apiErrorResponseData->message);
            }

            $createUserResponseData = CreateUserResponseData::fromResponse($response);

            return new Bot(
                id: Uuid::random($this->uuidManager),
                userId: new AutoId($createUserResponseData->user_id),
                name: new Username($name),
                password: new Password($password, $this->hasher),
                registeredAt: UnixTimestamp::fromUnixTimestamp($createUserResponseData->register_date),
            );
        }
        catch (GuzzleException $e) {
            $apiErrorResponseData = ApiErrorResponseData::fromGuzzleException($e);
            throw new BotCreationException($apiErrorResponseData->message, $e);
        }
        catch (ValueObjectException $e) {
            throw new BotCreationException($e->getMessage(), $e);
        }
    }
}
