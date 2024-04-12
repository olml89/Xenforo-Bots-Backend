<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class ApiKey implements StringValueObject
{
    private function __construct(
        private string $api_key,
    ) {}

    /**
     * @throws InvalidApiKeyException
     */
    public static function create(string $api_key): self
    {
        self::ensureItHasExactly32Characters($api_key);

        return new self($api_key);
    }

    /**
     * @throws InvalidApiKeyException
     */
    private static function ensureItHasExactly32Characters(string $api_key): void
    {
        if (strlen($api_key) !== 32) {
            throw new InvalidApiKeyException($api_key);
        }
    }

    public function value(): string
    {
        return $this->api_key;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
