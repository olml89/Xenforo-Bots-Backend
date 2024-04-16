<?php declare(strict_types=1);

namespace Tests\Bot\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidPasswordException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;

final class PasswordTest extends TestCase
{
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
}
