<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use Illuminate\Foundation\Testing\TestCase;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\InvalidUnixTimestampException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;

final class UnixTimestampTest extends TestCase
{
    public function testItDoesNotAllowInvalidUnixTimestamps(): void
    {
        $value = -10000000000000;

        $this->expectExceptionObject(new InvalidUnixTimestampException($value));

        UnixTimestamp::create($value);
    }

    public function testItCreatesUnixTimestamp(): void
    {
        $value = time();

        $unixTimestamp = UnixTimestamp::create($value);

        $this->assertEquals(
            $value,
            $unixTimestamp->timestamp()
        );
    }
}
