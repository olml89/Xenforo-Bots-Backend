<?php declare(strict_types=1);

namespace Tests\Bot\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;

final class UsernameTest extends TestCase
{
    public function testItDoesNotAllowEmptyUsernames(): void
    {
        $value = '';

        $this->expectExceptionObject(InvalidUsernameException::empty());

        Username::create($value);
    }

    public function testItDoesNotAllowUsernamesLongerThan50Chars(): void
    {
        $value = Str::random(51);

        $this->expectExceptionObject(InvalidUsernameException::tooLong($value));

        Username::create($value);
    }

    public function testItCreatesUsername(): void
    {
        $value = Str::random(50);

        $username = Username::create($value);

        $this->assertEquals(
            $value,
            $username->value()
        );
        $this->assertEquals(
            $value,
            (string)$username
        );
    }
}
