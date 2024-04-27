<?php declare(strict_types=1);

namespace Tests\Content\Unit\Domain;

use Database\Factories\ValueObjects\AutoIdFactory;
use olml89\XenforoBotsBackend\Content\Domain\AutoId;
use olml89\XenforoBotsBackend\Content\Domain\InvalidAutoIdException;
use Tests\TestCase;

final class AutoIdTest extends TestCase
{
    private readonly AutoIdFactory $autoIdFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->autoIdFactory = $this->resolve(AutoIdFactory::class);
    }

    public function testItDoesNotAllowNegativeValues(): void
    {
        $value = -1;

        $this->expectExceptionObject(
            new InvalidAutoIdException($value)
        );

        AutoId::create($value);
    }

    public function testItDoesNotAllowZeroValue(): void
    {
        $value = 0;

        $this->expectExceptionObject(
            new InvalidAutoIdException($value)
        );

        AutoId::create($value);
    }

    public function testItCreatesAutoId(): void
    {
        $value = fake()->numberBetween(1, 100);

        $autoId = AutoId::create($value);

        $this->assertEquals(
            $value,
            $autoId->value()
        );
        $this->assertEquals(
            (string)$value,
            (string)$autoId
        );
    }

    public function testItChecksEquality(): void
    {
        $autoId = $this->autoIdFactory->create();
        $equalAutoId = clone $autoId;
        $differentAutoId = $this->autoIdFactory->create();

        $this->assertTrue(
            $autoId->equals($equalAutoId)
        );
        $this->assertFalse(
            $autoId->equals($differentAutoId)
        );
    }
}
