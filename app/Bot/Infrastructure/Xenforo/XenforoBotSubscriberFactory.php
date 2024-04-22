<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use Illuminate\Config\Repository as Config;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\InvalidUrlException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotSubscriberFactory
{
    private Url $backendUrl;
    private ApiKey $backendApiKey;

    /**
     * @throws InvalidUrlException
     * @throws InvalidApiKeyException
     */
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
        Config $config,
        UrlValidator $urlValidator
    ) {
        $this->backendUrl = Url::create(
            $config->get('app.url'),
            $urlValidator,
        );

        $this->backendApiKey = ApiKey::create($config->get('app.api_key'));
    }

    public function create(): XenforoBotSubscriber
    {
        return new XenforoBotSubscriber(
            xenforoApiConsumer: $this->xenforoApiConsumer,
            backendUrl: $this->backendUrl,
            backendApiKey: $this->backendApiKey
        );
    }
}
