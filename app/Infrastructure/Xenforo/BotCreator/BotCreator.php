<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Xenforo\BotCreator;

use GuzzleHttp\Exception\GuzzleException;
use olml89\XenforoBots\Domain\Bot\Bot;
use olml89\XenforoBots\Domain\Bot\BotCreationException;
use olml89\XenforoBots\Domain\Bot\BotCreator as BotCreatorContract;
use olml89\XenforoBots\Domain\Bot\Username;
use olml89\XenforoBots\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBots\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Infrastructure\UuidManager\UuidManager;
use olml89\XenforoBots\Infrastructure\Xenforo\ApiConsumer;
use olml89\XenforoBots\Infrastructure\Xenforo\ApiErrorResponseData;

final class BotCreator implements BotCreatorContract
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
                registeredAt: new UnixTimestamp($createUserResponseData->register_date),
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
