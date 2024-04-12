<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData as UserRequestData;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotData as UserResponseData;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Auth\RequestData as AuthRequestData;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Post\RequestData as PostRequestData;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Post\ResponseData as PostResponseData;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Subscription\RequestData as SubscriptionRequestData;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Subscription\ResponseData as SubscriptionResponseData;
use Symfony\Component\HttpFoundation\Response;

final class XenforoApi
{
    public function __construct(
        private readonly XenforoApiConsumer $apiConsumer,
    ) {}

    public function apiUrl(): Url
    {
        return $this->apiConsumer->apiUrl();
    }

    /**
     * @throws XenforoApiException
     */
    public function postAuth(AuthRequestData $authRequestData): UserResponseData
    {
        try {
            $response = $this->apiConsumer->post(
                endpoint: '/auth',
                data: $authRequestData,
            );

            return UserResponseData::fromResponse($response);
        }
        catch (RequestException $e) {
            throw XenforoApiException::fromResponse($e->getResponse());
        }
        catch (GuzzleException $e) {
            throw XenforoApiException::fromGuzzleException($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function postUser(UserRequestData $userRequestData): UserResponseData
    {
        try {
            $response = $this->apiConsumer->post(
                endpoint: 'bots',
                data: $userRequestData,
            );

            return UserResponseData::fromResponse($response);
        }
        catch (RequestException $e) {
            throw XenforoApiException::fromResponse($e->getResponse());
        }
        catch (GuzzleException $e) {
            throw XenforoApiException::fromGuzzleException($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function getSubscription(int $user_id, string $webhook): ?SubscriptionResponseData
    {
        try {
            $response = $this->apiConsumer->get(
                endpoint: '/subscriptions',
                query: [$user_id, $webhook],
            );

            return SubscriptionResponseData::fromResponse($response);
        }
        catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() === Response::HTTP_NOT_FOUND) {
                return null;
            }

            throw XenforoApiException::fromResponse($e->getResponse());
        }
        catch (GuzzleException $e) {
            throw XenforoApiException::fromGuzzleException($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function postSubscription(SubscriptionRequestData $subscriptionRequestData): SubscriptionResponseData
    {
        try {
            $response = $this->apiConsumer->post(
                endpoint: '/subscriptions',
                data: $subscriptionRequestData,
            );

            return SubscriptionResponseData::fromResponse($response);
        }
        catch (RequestException $e) {
            throw XenforoApiException::fromResponse($e->getResponse());
        }
        catch (GuzzleException $e) {
            throw XenforoApiException::fromGuzzleException($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function deleteSubscription(int $user_id, string $webhook): void
    {
        try {
            $this->apiConsumer->delete(
                endpoint: '/subscriptions',
                query: [$user_id, $webhook],
            );
        }
        catch (RequestException $e) {
            throw XenforoApiException::fromResponse($e->getResponse());
        }
        catch (GuzzleException $e) {
            throw XenforoApiException::fromGuzzleException($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function postPost(int $user_id, PostRequestData $postRequestData): PostResponseData
    {
        try {
            $response = $this->apiConsumer->post(
                endpoint: '/posts',
                data: $postRequestData,
                headers: ['XF-Api-User' => $user_id],
            );

            return PostResponseData::fromResponse($response);
        }
        catch (RequestException $e) {
            throw XenforoApiException::fromResponse($e->getResponse());
        }
        catch (GuzzleException $e) {
            throw XenforoApiException::fromGuzzleException($e);
        }
    }
}
