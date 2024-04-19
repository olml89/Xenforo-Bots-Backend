<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain;

use JsonSerializable as JsonSerializableContract;
use Stringable;

abstract class JsonSerializable implements JsonSerializableContract, Stringable
{
    public function jsonSerialize(): array
    {
        return (array)$this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s',
            json_encode($this, JSON_PRETTY_PRINT),
            PHP_EOL,
        );
    }
}
