<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;

final readonly class BehaviourCreator
{
    public function __construct(
        private UuidGenerator $uuidGenerator,
    ) {}

    public function create(BehaviourName $behaviourName, BehaviourPattern $behaviourPattern): Behaviour
    {
        return new Behaviour(
            behaviourId: $this->uuidGenerator->generate(),
            behaviourName: $behaviourName,
            behaviourPattern: $behaviourPattern
        );
    }
}
