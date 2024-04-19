<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use GuzzleHttp\HandlerStack;
use Illuminate\Config\Repository as Config;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\InvalidUrlException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;

final readonly class XenforoApiConsumerFactory
{
    private Url $apiUrl;
    private ApiKey $superUserApiKey;

    /**
     * @throws InvalidUrlException
     * @throws InvalidApiKeyException
     */
    public function __construct(Config $config, UrlValidator $urlValidator)
    {
        $this->apiUrl = Url::create(
            $config->get('xenforo.api_url'),
            $urlValidator
        );

        $this->superUserApiKey = ApiKey::create($config->get('xenforo.super_user_api_key'));
    }

    public function create(?HandlerStack $handlerStack = null): XenforoApiConsumer
    {
        return new XenforoApiConsumer(
            apiUrl: $this->apiUrl,
            superUserApiKey: $this->superUserApiKey,
            handlerStack: $handlerStack,
        );
    }
}
