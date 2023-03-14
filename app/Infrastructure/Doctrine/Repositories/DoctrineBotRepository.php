<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Doctrine\Repositories;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBots\Domain\Bot\Bot;
use olml89\XenforoBots\Domain\Bot\BotRepository;
use olml89\XenforoBots\Domain\Bot\BotStorageException;

final class DoctrineBotRepository extends EntityRepository implements BotRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Bot::class),
        );
    }

    public function save(Bot $bot): void
    {
        try {
            $this->getEntityManager()->persist($bot);
            $this->getEntityManager()->flush();
        }
        catch (Exception $doctrineException) {
            throw new BotStorageException($doctrineException->getMessage(), $doctrineException);
        }
    }
}
