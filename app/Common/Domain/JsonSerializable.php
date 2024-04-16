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
        return json_encode($this);
    }
}
