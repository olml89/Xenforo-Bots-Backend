<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class BehaviourPatternHandler implements StringValueObject
{
    private function __construct(
        /**
         * @var class-string<BehaviourPattern>
         */
        private string $behaviourPatternHandler,
    ) {}

    /**
     * @throws InvalidBehaviourPatternHandlerException
     */
    public static function create(string $behaviourPatternHandler): self
    {
        self::ensureIsNotEmptyAndAtMost100Characters($behaviourPatternHandler);
        self::ensureClassExistsAndImplementsBehaviourPattern($behaviourPatternHandler);

        return new self($behaviourPatternHandler);
    }

    /**
     * @throws InvalidBehaviourPatternHandlerException
     */
    private static function ensureIsNotEmptyAndAtMost100Characters(string $behaviourPatternHandler): void
    {
        if (strlen($behaviourPatternHandler) === 0) {
            throw InvalidBehaviourPatternHandlerException::empty();
        }

        if (strlen($behaviourPatternHandler) > 100) {
            throw InvalidBehaviourPatternHandlerException::tooLong(100, $behaviourPatternHandler);
        }
    }

    /**
     * @throws InvalidBehaviourPatternHandlerException
     */
    private static function ensureClassExistsAndImplementsBehaviourPattern(string $behaviourPatternHandler): void
    {
        if (!class_exists($behaviourPatternHandler)) {
            throw InvalidBehaviourPatternHandlerException::doesNotExist($behaviourPatternHandler);
        }

        if (!is_a($behaviourPatternHandler, class: BehaviourPattern::class, allow_string: true)) {
            throw InvalidBehaviourPatternHandlerException::invalidBehaviourPattern($behaviourPatternHandler);
        }
    }

    /**
     * @return class-string<BehaviourPattern>
     */
    public function value(): string
    {
        return $this->behaviourPatternHandler;
    }

    /**
     * @return class-string<BehaviourPattern>
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
