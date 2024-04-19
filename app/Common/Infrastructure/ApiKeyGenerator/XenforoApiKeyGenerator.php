<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\ApiKeyGenerator;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKeyGenerator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;
use Random\RandomException;

final readonly class XenforoApiKeyGenerator implements ApiKeyGenerator
{
    private const int CHAR_LENGTH = 32;

    /**
     * @var array<string, string>
     */
    private const array CHAR_CONVERSIONS = [
        '=' => '',
        "\r" => '',
        "\n" => '',
        '+' => '-',
        '/' => '_',
    ];

    /**
     * @throws RandomException
     * @throws InvalidApiKeyException
     */
    public function generate(): ApiKey
    {
        $randomString = random_bytes(self::CHAR_LENGTH);

        $convertedString = strtr(
            base64_encode($randomString),
            self::CHAR_CONVERSIONS
        );

        return ApiKey::create(substr(
            string: $convertedString,
            offset: 0,
            length: self::CHAR_LENGTH
        ));
    }
}
