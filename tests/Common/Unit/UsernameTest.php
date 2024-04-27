<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use Database\Factories\ValueObjects\UsernameFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\InvalidUsernameException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use Tests\TestCase;

final class UsernameTest extends TestCase
{
    private readonly UsernameFactory $usernameFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameFactory = $this->resolve(UsernameFactory::class);
    }

    public function testItDoesNotAllowUsernamesShorterThan3Chars(): void
    {
        $value = Str::random(2);

        $this->expectExceptionObject(
            InvalidUsernameException::tooShort($value)
        );

        Username::create($value);
    }

    public function testItDoesNotAllowUsernamesLongerThan50Chars(): void
    {
        $value = Str::random(51);

        $this->expectExceptionObject(
            InvalidUsernameException::tooLong($value)
        );

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

    public function testItChecksEquality(): void
    {
        $username = $this->usernameFactory->create();
        $equalUsername = clone $username;
        $differentUsername = $this->usernameFactory->create();

        $this->assertTrue(
            $username->equals($equalUsername)
        );
        $this->assertFalse(
            $username->equals($differentUsername)
        );
    }
}
