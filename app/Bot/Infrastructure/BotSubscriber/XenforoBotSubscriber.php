<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotSubscriber;

use DateTimeImmutable;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Config\Repository as Config;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Bot\Domain\BotSubscriber;
use olml89\XenforoBots\Bot\Domain\BotSubscriptionException;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiConsumer;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiErrorResponseData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription\Create\RequestData as CreateSubscriptionRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription\Create\ResponseData as CreateSubscriptionResponseData;
use olml89\XenforoBots\Subscription\Domain\Subscription;

final class XenforoBotSubscriber implements BotSubscriber
{
    public function __construct(
        private readonly Config $config,
        private readonly ApiConsumer $apiConsumer,
        private readonly UuidManager $uuidManager,
    ) {}

    /**
     * @throws BotSubscriptionException
     */
    public function subscribe(Bot $bot, string $password): Subscription
    {
        try {
            if ($bot->isSubscribed()) {
                throw BotSubscriptionException::alreadySubscribed($bot);
            }

            $response = $this->apiConsumer->post(
                endpoint: '/subscriptions',
                data: new CreateSubscriptionRequestData(
                    user_id: $bot->userId()->toInt(),
                    password: $password,
                    webhook: $this->config->get('app')['url'],
                )
            );

            if ($response->getStatusCode() !== 200) {
                $apiErrorResponseData = ApiErrorResponseData::fromResponse($response);
                throw new BotSubscriptionException($apiErrorResponseData->message);
            }

            $createSubscriptionResponseData = CreateSubscriptionResponseData::fromResponse($response);

            return new Subscription(
                id: new Uuid($createSubscriptionResponseData->id, $this->uuidManager),
                bot: $bot,
                xenforoUrl: $this->apiConsumer->apiUrl(),
                subscribedAt: UnixTimestamp::toDateTimeImmutable($createSubscriptionResponseData->subscribed_at),
            );
        }
        catch (GuzzleException $e) {
            $apiErrorResponseData = ApiErrorResponseData::fromGuzzleException($e);
            throw new BotSubscriptionException($apiErrorResponseData->message, $e);
        }
        catch (ValueObjectException $e) {
            throw new BotSubscriptionException($e->getMessage(), $e);
        }
    }
}
