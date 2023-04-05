<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects\Url;

interface UrlValidator
{
    public function isValid(string $url): bool;
}
