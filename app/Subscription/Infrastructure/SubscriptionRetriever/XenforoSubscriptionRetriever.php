<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Infrastructure\SubscriptionRetriever;

use GuzzleHttp\Exception\GuzzleException;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiConsumer;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiErrorResponseData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription\ResponseData as SubscriptionResponseData;
use olml89\XenforoBots\Subscription\Domain\Subscription;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRetrievalException;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRetriever;
use Symfony\Component\HttpFoundation\Response;

final class XenforoSubscriptionRetriever implements SubscriptionRetriever
{
    public function __construct(
        private readonly ApiConsumer $apiConsumer,
        private readonly UuidManager $uuidManager,
    ) {}

    /**
     * @throws SubscriptionRetrievalException
     */
    public function get(Bot $bot): ?Subscription
    {
        try {
            $response = $this->apiConsumer->get(
                endpoint: sprintf(
                    '/subscriptions/?user_id=%s&webhook=%s',
                    $bot->userId()->toInt(),
                    urlencode((string)$this->apiConsumer->appUrl()),
                )
            );

            if ($response->getStatusCode() === Response::HTTP_NOT_FOUND) {
                return null;
            }

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $subscriptionResponseData = SubscriptionResponseData::fromResponse($response);

                return new Subscription(
                    id: new Uuid($subscriptionResponseData->id, $this->uuidManager),
                    bot: $bot,
                    xenforoUrl: $this->apiConsumer->apiUrl(),
                    subscribedAt: UnixTimestamp::toDateTimeImmutable($subscriptionResponseData->subscribed_at),
                );
            }

            throw SubscriptionRetrievalException::xenforoError(
                $this->apiConsumer->apiUrl(),
                $response->getStatusCode(),
            );
        }
        catch (GuzzleException $e) {
            $apiErrorResponseData = ApiErrorResponseData::fromGuzzleException($e);
            throw new SubscriptionRetrievalException($apiErrorResponseData->message, $e);
        }
        catch (ValueObjectException $e) {
            throw new SubscriptionRetrievalException($e->getMessage(), $e);
        }
    }
}
