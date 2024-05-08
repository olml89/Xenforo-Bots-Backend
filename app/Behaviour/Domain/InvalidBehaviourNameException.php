<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidBehaviourNameException extends ValueObjectException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function empty(): self
    {
        return new self('Behaviour name cannot be empty');
    }

    public static function tooLong(int $maxLength, string $behaviourName): self
    {
        return new self(
            sprintf(
                'Behaviour name cannot exceed %s characters length,  \'%s\' provided with %s characters',
                $maxLength,
                $behaviourName,
                strlen($behaviourName),
            )
        );
    }
}
