<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url;

final readonly class UrlFactory
{
    public function __construct(
        private UrlValidator $urlValidator,
    ) {}

    public function create(string $url): Url
    {
        return new Url($url, $this->urlValidator);
    }
}
