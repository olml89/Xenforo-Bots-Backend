<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Domain;

use Database\Factories\ValueObjects\PasswordFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidPasswordException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use Tests\TestCase;

final class PasswordTest extends TestCase
{
    private readonly PasswordFactory $passwordFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordFactory = $this->resolve(PasswordFactory::class);
    }

    public function testItDoesNotAllowEmptyPasswords(): void
    {
        $value = '';

        $this->expectExceptionObject(new InvalidPasswordException());

        Password::create($value);
    }

    public function testItCreatesPassword(): void
    {
        $value = Str::random(fake()->numberBetween(1, 100));

        $password = Password::create($value);

        $this->assertEquals(
            $value,
            $password->value()
        );
        $this->assertEquals(
            $value,
            (string)$password)
        ;
    }

    public function testItChecksEquality(): void
    {
        $password = $this->passwordFactory->create();
        $equalPassword = clone $password;
        $differentPassword = $this->passwordFactory->create();

        $this->assertTrue(
            $password->equals($equalPassword)
        );
        $this->assertFalse(
            $password->equals($differentPassword)
        );
    }
}
