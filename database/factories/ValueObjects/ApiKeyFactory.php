<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;

final class ApiKeyFactory
{
    public function create(): ApiKey
    {
        return ApiKey::create(Str::random(32));
    }
}
