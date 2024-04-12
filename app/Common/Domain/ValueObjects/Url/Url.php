<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class Url implements StringValueObject
{
    private function __construct(
        private string $url,
    ) {}

    /**
     * @throws InvalidUrlException
     */
    public static function create(string $url, UrlValidator $validator): self
    {
        self::ensureIsAValidUrl($url, $validator);

        return new self($url);
    }

    /**
     * @throws InvalidUrlException
     */
    private static function ensureIsAValidUrl(string $url, UrlValidator $validator): void
    {
        if (!$validator->isValid($url)) {
            throw new InvalidUrlException($url);
        }
    }

    public function urlencode(): string
    {
        return urlencode((string)$this);
    }

    public function value(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
