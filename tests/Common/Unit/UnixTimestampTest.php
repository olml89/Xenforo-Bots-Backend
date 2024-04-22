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

        $this->expectExceptionObject(
            InvalidUnixTimestampException::invalid()
        );

        UnixTimestamp::create($value);
    }

    public function testItCreatesUnixTimestampFromTimestamp(): void
    {
        $value = time();

        $unixTimestamp = UnixTimestamp::create($value);

        $this->assertEquals(
            $value,
            $unixTimestamp->timestamp()
        );
    }

    public function testItDoesNotAllowInvalidDateTimeStringsForFormats(): void
    {
        $value = '2024-19-04 17:10:00';
        $format = 'Y/m/d H:i:s';

        $this->expectExceptionObject(
            InvalidUnixTimestampException::format($format, $value)
        );

        UnixTimestamp::createFromFormat($format, $value);
    }

    public function testItCreatesUnixTimestampFromValidDateTimeStringAndFormat(): void
    {
        // Unix epoch
        $value = '1970/01/01 00:00:00';
        $format = 'Y/m/d H:i:s';

        $unixTimestamp = UnixTimestamp::createFromFormat($format, $value);

        $this->assertEquals(
            $value,
            $unixTimestamp->format($format)
        );
        $this->assertEquals(
            0,
            $unixTimestamp->timestamp()
        );
    }
}
