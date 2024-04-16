<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;

final class ApiKeyTest extends TestCase
{
    public function testItDoesNotAllowApiKeysShorterThan32Characters(): void
    {
        $value = '';

        $this->expectExceptionObject(new InvalidApiKeyException($value));

        ApiKey::create($value);
    }

    public function testItDoesNotAllowApiKeysLongerThan32Characters(): void
    {
        $value = Str::random(33);

        $this->expectExceptionObject(new InvalidApiKeyException($value));

        ApiKey::create($value);
    }

    public function testItCreatesApiKey(): void
    {
        $value = Str::random(32);

        $apiKey = ApiKey::create($value);

        $this->assertEquals(
            $value,
            $apiKey->value()
        );
        $this->assertEquals(
            $value,
            (string)$apiKey
        );
    }
}
