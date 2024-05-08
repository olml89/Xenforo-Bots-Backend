<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidBehaviourPatternHandlerException extends ValueObjectException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function empty(): self
    {
        return new self('Behaviour pattern handler cannot be empty');
    }

    public static function tooLong(int $maxLength, string $behaviourName): self
    {
        return new self(
            sprintf(
                'Behaviour pattern handler cannot exceed %s characters length,  \'%s\' provided with %s characters',
                $maxLength,
                $behaviourName,
                strlen($behaviourName),
            )
        );
    }

    public static function doesNotExist(string $behaviourPatternHandler): self
    {
        return new self(
            sprintf(
                'Class %s does not exist',
                $behaviourPatternHandler,
            )
        );
    }

    public static function invalidBehaviourPattern(string $behaviourPatternHandler): self
    {
        return new self(
            sprintf(
                'Class %s does not implement %s',
                $behaviourPatternHandler,
                BehaviourPattern::class,
            )
        );
    }

    public static function notLoaded(BehaviourPatternHandler $behaviourPatternHandler): self
    {
        return new self(
            sprintf(
                'BehaviourPattern %s is not loaded into the BehaviourPatternManager',
                $behaviourPatternHandler,
            )
        );
    }
}
