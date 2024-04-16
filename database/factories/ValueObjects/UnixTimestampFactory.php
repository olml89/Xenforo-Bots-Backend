<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;

final class UnixTimestampFactory
{
    public function create(): UnixTimestamp
    {
        return UnixTimestamp::create(time());
    }
}
