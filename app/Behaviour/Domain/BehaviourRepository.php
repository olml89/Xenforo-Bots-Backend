<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Criteria;

interface BehaviourRepository
{
    public function getOneBy(Criteria $criteria): ?Behaviour;

    /**
     * @throws BehaviourStorageException
     */
    public function save(Behaviour $behaviour): void;
}
