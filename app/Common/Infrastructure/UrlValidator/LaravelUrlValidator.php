<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\UrlValidator;

use Illuminate\Validation\Factory as ValidationFactory;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\UrlValidator;

final class LaravelUrlValidator implements UrlValidator
{
    public function __construct(
        private readonly ValidationFactory $validationFactory,
    ) {}

    public function isValid(string $url): bool
    {
        return $this->validationFactory->make([$url], ['url'])->passes();
    }
}
