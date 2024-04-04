<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use Illuminate\Config\Repository as Config;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;

final class XenforoApiFactory
{
    private readonly string $apiKey;
    private readonly Url $apiUrl;

    public function __construct(Config $config, UrlValidator $urlValidator)
    {
        $this->apiKey = $config->get('xenforo')['api_key'];

        $this->apiUrl = Url::create(
            $config->get('xenforo')['api_url'],
            $urlValidator,
        );
    }

    public function create(array $config = []): XenforoApi
    {
        $apiConsumer = new ApiConsumer(
            apiKey: $this->apiKey,
            apiUrl: $this->apiUrl,
            config: $config,
        );

        return new XenforoApi($apiConsumer);
    }
}
