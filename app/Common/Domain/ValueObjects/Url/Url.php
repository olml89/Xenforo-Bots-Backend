<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects\Url;

use olml89\XenforoBots\Common\Domain\ValueObjects\StringValueObject;

final class Url extends StringValueObject
{
    private function __construct(string $url)
    {
        parent::__construct($url);
    }

    public static function create(string $url, UrlValidator $validator): self
    {
        self::ensureIsAValidUrl($url, $validator);

        return new self($url);
    }

    public function withPath(string $path): self
    {
        return new self($this->value.$path);
    }

    private static function ensureIsAValidUrl(string $url, UrlValidator $validator): void
    {
        if (!$validator->isValid($url)) {
            throw new InvalidUrlException($url);
        }
    }
}
