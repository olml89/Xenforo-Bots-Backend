<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class BehaviourName implements StringValueObject
{
    private function __construct(
        private string $behaviourName,
    ) {}

    /**
     * @throws InvalidBehaviourNameException
     */
    public static function create(string $behaviourName): self
    {
        self::ensureItIsNotEmptyAndAtMost50Characters($behaviourName);

        return new self($behaviourName);
    }

    /**
     * @throws InvalidBehaviourNameException
     */
    private static function ensureItIsNotEmptyAndAtMost50Characters(string $behaviourName): void
    {
        if (strlen($behaviourName) === 0) {
            throw InvalidBehaviourNameException::empty();
        }

        if (strlen($behaviourName) > 50) {
            throw InvalidBehaviourNameException::tooLong(50, $behaviourName);
        }
    }

    public function value(): string
    {
        return $this->behaviourName;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
