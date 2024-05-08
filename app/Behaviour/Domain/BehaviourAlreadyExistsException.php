<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityAlreadyExistsException;

final class BehaviourAlreadyExistsException extends EntityAlreadyExistsException
{
    public static function behaviour(Behaviour $behaviour): self
    {
        return new self(
            sprintf('Behaviour with name \'%s\' already exists (id: %s)',
                $behaviour->behaviourName(),
                $behaviour->behaviourId(),
            )
        );
    }
}
