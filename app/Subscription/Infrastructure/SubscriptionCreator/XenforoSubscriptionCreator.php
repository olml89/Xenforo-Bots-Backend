<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Infrastructure\SubscriptionCreator;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Config\Repository as Config;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiConsumer;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiErrorResponseData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription\RequestData as SubscriptionRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription\ResponseData as SubscriptionResponseData;
use olml89\XenforoBots\Subscription\Domain\SubscriptionCreator;
use olml89\XenforoBots\Subscription\Domain\Subscription;
use olml89\XenforoBots\Subscription\Domain\SubscriptionCreationException;
use Symfony\Component\HttpFoundation\Response;

final class XenforoSubscriptionCreator implements SubscriptionCreator
{
    public function __construct(
        private readonly ApiConsumer $apiConsumer,
        private readonly UuidManager $uuidManager,
    ) {}

    /**
     * @throws SubscriptionCreationException
     */
    public function subscribe(Bot $bot, string $password): Subscription
    {
        try {
            if ($bot->isSubscribed()) {
                throw SubscriptionCreationException::alreadySubscribed($bot);
            }

            $response = $this->apiConsumer->post(
                endpoint: '/subscriptions',
                data: new SubscriptionRequestData(
                    user_id: $bot->userId()->toInt(),
                    password: $password,
                    webhook: (string)$this->apiConsumer->appUrl(),
                )
            );

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                $apiErrorResponseData = ApiErrorResponseData::fromResponse($response);
                throw new SubscriptionCreationException($apiErrorResponseData->message);
            }

            $createSubscriptionResponseData = SubscriptionResponseData::fromResponse($response);

            $subscription = new Subscription(
                id: new Uuid($createSubscriptionResponseData->id, $this->uuidManager),
                bot: $bot,
                xenforoUrl: $this->apiConsumer->apiUrl(),
                subscribedAt: UnixTimestamp::toDateTimeImmutable($createSubscriptionResponseData->subscribed_at),
            );
            $bot->subscribe($subscription);

            return $subscription;
        }
        catch (GuzzleException $e) {
            $apiErrorResponseData = ApiErrorResponseData::fromGuzzleException($e);
            throw new SubscriptionCreationException($apiErrorResponseData->message, $e);
        }
        catch (ValueObjectException $e) {
            throw new SubscriptionCreationException($e->getMessage(), $e);
        }
    }
}
