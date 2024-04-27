<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use Database\Factories\ValueObjects\UuidFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use Tests\TestCase;

final class UuidTest extends TestCase
{
    private readonly UuidFactory $uuidFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uuidFactory = $this->resolve(UuidFactory::class);
    }

    public function testItDoesNotAllowInvalidUuids(): void
    {
        $value = Str::random();

        $this->expectExceptionObject(
            new InvalidUuidException($value)
        );

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

    public function testItChecksEquality(): void
    {
        $uuid = $this->uuidFactory->create();
        $equalUuid = clone $uuid;
        $differentUuid =$this->uuidFactory->create();

        $this->assertTrue(
            $uuid->equals($equalUuid)
        );
        $this->assertFalse(
            $uuid->equals($differentUuid)
        );
    }
}
