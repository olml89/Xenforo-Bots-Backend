<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class UuidTest extends TestCase
{
    public function testItDoesNotAllowInvalidUuids(): void
    {
        $value = Str::random();

        $this->expectExceptionObject(new InvalidUuidException($value));

        Uuid::create($value);
    }

    public function testItCreatesUuid(): void
    {
        $value = fake()->uuid();

        $uuid = Uuid::create($value);

        $this->assertEquals(
            $value,
            $uuid->value()
        );
        $this->assertEquals(
            $value,
            (string)$uuid
        );
    }

    public function testItCreatesRandomUuid(): void
    {
        $this->expectNotToPerformAssertions();

        Uuid::random();
    }
}
