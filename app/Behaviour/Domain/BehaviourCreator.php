<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;

final readonly class BehaviourCreator
{
    public function __construct(
        private BehaviourPatternManager $behaviourPatternManager,
        private UuidGenerator $uuidGenerator,
    ) {}

    /**
     * @throws InvalidBehaviourPatternHandlerException
     */
    public function create(BehaviourName $behaviourName, BehaviourPatternHandler $behaviourPatternHandler): Behaviour
    {
        $behaviourPattern = $this->behaviourPatternManager->get($behaviourPatternHandler);

        return new Behaviour(
            behaviourId: $this->uuidGenerator->generate(),
            behaviourName: $behaviourName,
            behaviourPattern: $behaviourPattern
        );
    }
}
