<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\BotCreator;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotData;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotCreator implements BotCreator
{
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
    ) {}

    /**
     * @throws BotCreationException
     * @throws BotValidationException
     */
    public function create(Username $username, Password $password): Bot
    {
        try {
            $xenforoBotCreationData = new XenforoBotCreationData(
                username: $username->value(),
                password: $password->value(),
            );

            $xenforoBotData = XenforoBotData::fromResponse(
                $this->xenforoApiConsumer->post(
                    'bots',
                    $xenforoBotCreationData
                )
            );

            return new Bot(
                botId: Uuid::create($xenforoBotData->bot_id),
                apiKey: ApiKey::create($xenforoBotData->api_key),
                username: $username,
                registeredAt: UnixTimestamp::create($xenforoBotData->created_at)->value(),
            );
            /*
            return new Bot(
                botId: Uuid::random(),
                apiKey: ApiKey::create(\Illuminate\Support\Str::random(32)),
                username: $username,
                registeredAt: new \DateTimeImmutable(), //UnixTimestamp::create(time()),
            );
            */
        }
        catch (XenforoApiException $e) {
            throw new BotCreationException($e);
        }
        catch (ValueObjectException $e) {
            throw new BotValidationException($e);
        }
    }
}
