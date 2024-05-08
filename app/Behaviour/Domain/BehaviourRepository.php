<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

interface BehaviourRepository
{
    public function getByBehaviourName(BehaviourName $behaviourName): ?Behaviour;

    /**
     * @throws BehaviourStorageException
     */
    public function save(Behaviour $behaviour): void;
}
