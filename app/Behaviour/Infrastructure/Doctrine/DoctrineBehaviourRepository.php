<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBotsBackend\Behaviour\Domain\Behaviour;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourRepository;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourSpecification;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourStorageException;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Criteria;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DoctrineCriteriaConverter;
use Throwable;

final class DoctrineBehaviourRepository extends EntityRepository implements BehaviourRepository
{
    public function __construct(
        private readonly DoctrineCriteriaConverter $doctrineCriteriaConverter,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Behaviour::class),
        );
    }

    public function getOneBy(BehaviourSpecification $specification): ?Behaviour
    {
        $doctrineCriteria = $this
            ->doctrineCriteriaConverter
            ->convert($specification->criteria());

        return $this
            ->matching($doctrineCriteria)
            ->first() ?: null;
    }

    /**
     * @throws BehaviourStorageException
     */
    public function save(Behaviour $behaviour): void
    {
        try {
            $this->getEntityManager()->persist($behaviour);
            $this->getEntityManager()->flush();
        }
        catch (Throwable $doctrineException) {
            throw BehaviourStorageException::fromException($doctrineException);
        }
    }
}
