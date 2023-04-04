<?php declare(strict_types=1);

namespace Tests\Common;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait InteractsWithXenforoApi
{
    private readonly MockHandler $requests;

    private function setUpGuzzleClient(): void
    {
        $this->requests = new MockHandler();

        $this->app->singleton(Client::class, function(): Client {
            return new Client([
                'handler' => HandlerStack::create($this->requests),
                'http_errors' => false,
            ]);
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
