<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Doctrine;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use Throwable;

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
     * @return Bot[]
     */
    public function allSubscribed(): array
    {
        return $this
            ->matching(
                new Criteria(Criteria::expr()->neq('subscription', null))
            )
            ->toArray();
    }

    public function get(Uuid $botId): ?Bot
    {
        return $this->find($botId);
    }

    public function getByUsername(Username $username): ?Bot
    {
        return $this->findOneBy([
            'username' => $username,
        ]);
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
            throw new BotStorageException($doctrineException);
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
            throw new BotStorageException($doctrineException);
        }
    }
}
