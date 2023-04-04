<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\Persistence;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;

final class DoctrineBotRepository extends EntityRepository implements BotRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Bot::class),
        );
    }

    /**
     * @throws BotStorageException
     */
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
