<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

final class ApiConsumer
{
    public function __construct(
        private readonly string $apiUrl,
        private readonly string $apiKey,
        private readonly Client $httpClient,
    ) { }

    /**
     * @throws GuzzleException
     */
    public function post(string $endpoint, JsonSerializable $data): ResponseInterface
    {
        return $this->httpClient->post(
            $this->apiUrl.$endpoint,
            ['form_params' => $data],
        );
    }
}
