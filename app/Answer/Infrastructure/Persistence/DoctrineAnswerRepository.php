<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Infrastructure\Persistence;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBots\Answer\Domain\Answer;
use olml89\XenforoBots\Answer\Domain\AnswerRepository;

final class DoctrineAnswerRepository extends EntityRepository implements AnswerRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Answer::class),
        );
    }

    public function save(Answer $answer): void
    {
        try {
            $this->getEntityManager()->persist($answer);
            $this->getEntityManager()->flush();
        }
        catch (Exception $doctrineException) {

        }
    }
}
