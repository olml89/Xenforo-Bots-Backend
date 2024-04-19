<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiUnprocessableEntityException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotCreator implements BotCreator
{
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
    ) {}

    /**
     * @throws BotValidationException
     * @throws BotCreationException
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
                    endpoint: 'bots',
                    data: $xenforoBotCreationData,
                )
            );

            return new Bot(
                botId: Uuid::create($xenforoBotData->bot_id),
                apiKey: ApiKey::create($xenforoBotData->api_key),
                username: $username
            );
        }
        /**
         * This will throw BotValidationException both for Domain invalid value objects from user or Xenforo API input,
         * and for 422 UnprocessableEntity responses from Xenforo too.
         */
        catch (ValueObjectException|XenforoApiUnprocessableEntityException $e) {
            throw new BotValidationException($e);
        }
        catch (XenforoApiException $e) {
            throw new BotCreationException($e);
        }
    }
}
