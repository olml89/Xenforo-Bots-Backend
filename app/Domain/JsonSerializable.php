<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain;

use JsonSerializable as JsonSerializableContract;

abstract class JsonSerializable implements JsonSerializableContract
{
    public function jsonSerialize(): array
    {
        return (array)$this;
    }
}
