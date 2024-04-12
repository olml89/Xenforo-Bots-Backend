<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\UrlValidator;

use Illuminate\Validation\Factory as ValidationFactory;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;

final readonly class LaravelUrlValidator implements UrlValidator
{
    public function __construct(
        private ValidationFactory $validationFactory,
    ) {}

    public function isValid(string $url): bool
    {
        return $this->validationFactory->make([$url], ['url'])->passes();
    }
}
