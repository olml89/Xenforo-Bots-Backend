<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey;

interface ApiKeyGenerator
{
    public function generate(): ApiKey;
}
