<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

final class BehaviourPatternManager
{
    /**
     * @var array<class-string<BehaviourPattern>, BehaviourPattern>
     */
    private array $behaviourPatterns = [];

    public function __construct(BehaviourPattern ...$behaviourPatterns)
    {
        foreach ($behaviourPatterns as $behaviourPattern) {
            $this->behaviourPatterns[$behaviourPattern::class] = $behaviourPattern;
        }
    }

    /**
     * @throws InvalidBehaviourPatternHandlerException
     */
    public function get(BehaviourPatternHandler $behaviourPatternHandler): BehaviourPattern
    {
        if (!array_key_exists((string) $behaviourPatternHandler, $this->behaviourPatterns)) {
            throw InvalidBehaviourPatternHandlerException::notLoaded($behaviourPatternHandler);
        }

        return $this->behaviourPatterns[(string)$behaviourPatternHandler];
    }
}
