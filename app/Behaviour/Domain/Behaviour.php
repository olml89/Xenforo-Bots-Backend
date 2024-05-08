<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final readonly class Behaviour
{
    public function __construct(
        private Uuid $behaviourId,
        private BehaviourName $behaviourName,
        private BehaviourPattern $behaviourPattern,
    ) {}

    public function behaviourId(): Uuid
    {
        return $this->behaviourId;
    }

    public function behaviourName(): BehaviourName
    {
        return $this->behaviourName;
    }

    public function behaviourPattern(): BehaviourPattern
    {
        return $this->behaviourPattern;
    }
}
