<?php declare(strict_types=1);

namespace Tests\Common;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiConsumer;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiFactory;
use Psr\Http\Message\ResponseInterface;

trait InteractsWithXenforoApi
{
    private readonly MockHandler $requests;

    private function setUpXenforoApi(): void
    {
        $this->requests = new MockHandler();

        $this->app->singleton(XenforoApi::class, function(): XenforoApi {
            /** @var XenforoApiFactory $xenforoApiFactory */
            $xenforoApiFactory = $this->app->get(XenforoApiFactory::class);

            return $xenforoApiFactory->create(['handler' => HandlerStack::create($this->requests)]);
        });
    }

    private function createBadRequestResponse(string $code, string $message): ResponseInterface
    {
        return new Response(
            status: 400,
            body: json_encode([
                'errors' => [
                    [
                        'code' => $code,
                        'message' => $message,
                    ]
                ]
            ]),
        );
    }

    private function createUserCreatedResponse(int $user_id, int $register_date_timestamp): ResponseInterface
    {
        return new Response(
            status: 200,
            body: json_encode([
                'success' => true,
                'user' => [
                    'user_id' => $user_id,
                    'register_date' => $register_date_timestamp,
                ]
            ])
        );
    }
}
