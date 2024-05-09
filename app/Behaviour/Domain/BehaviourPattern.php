<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Content\Domain\Content;
use Stringable;

abstract class BehaviourPattern implements Stringable
{
    abstract public function reactTo(Content $content, string $processedMessage): string;

    public function equals(BehaviourPattern $behaviourPattern): bool
    {
        return (string)$this === (string)$behaviourPattern;
    }

    public function __toString(): string
    {
        return $this::class;
    }
}
