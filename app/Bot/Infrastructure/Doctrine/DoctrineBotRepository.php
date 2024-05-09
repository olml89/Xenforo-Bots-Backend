<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotSpecification;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DoctrineCriteriaConverter;
use Throwable;

final class DoctrineBotRepository extends EntityRepository implements BotRepository
{
    public function __construct(
        private readonly DoctrineCriteriaConverter $doctrineCriteriaConverter,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Bot::class),
        );
    }

    /**
     * @return Bot[]
     */
    public function all(): array
    {
        return $this->findAll();
    }

    public function get(Uuid $botId): ?Bot
    {
        return $this->find($botId);
    }

    public function getOneBy(BotSpecification $specification): ?Bot
    {
        $doctrineCriteria = $this
            ->doctrineCriteriaConverter
            ->convert($specification->criteria());

        return $this
            ->matching($doctrineCriteria)
            ->first() ?: null;
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
        catch (Throwable $doctrineException) {
            throw BotStorageException::fromException($doctrineException);
        }
    }

    /**
     * @throws BotStorageException
     */
    public function delete(Bot $bot): void
    {
        try {
            $this->getEntityManager()->remove($bot);
            $this->getEntityManager()->flush();
        }
        catch (Throwable $doctrineException) {
            throw BotStorageException::fromException($doctrineException);
        }
    }
}
