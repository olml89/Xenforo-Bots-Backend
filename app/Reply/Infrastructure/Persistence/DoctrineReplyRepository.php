<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Infrastructure\Persistence;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBots\Reply\Domain\Reply;
use olml89\XenforoBots\Reply\Domain\ReplyRepository;
use olml89\XenforoBots\Reply\Domain\ReplyStorageException;

final class DoctrineReplyRepository extends EntityRepository implements ReplyRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Reply::class),
        );
    }

    public function getNextDeliverable(): ?Reply
    {
        /**
         * Reason to use DQL instead of findOneBy:
         * https://github.com/doctrine/orm/issues/9505
         *
         * Reply -> Bot -> Subscription
         *
         * If Bot is set as EAGER loading it doesn't try to update the uuid when getting $reply->bot(),
         * but because Subscription is on a 2nd grade of inheritance, even though is set as EAGER loading,
         * it will always be a proxy if we do $reply->bot()->subscription() (but not if we get a Bot
         * through BotRepository and do $bot->subscription()).
         *
         * So we have to mount the entities manually through DQL, joining the relationships is like eager-loading
         * them.
         */
        $results = $this
            ->getEntityManager()
            ->getRepository(Reply::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.bot', 'b')
            ->leftJoin('b.subscription', 's')
            ->select('a, b, s')
            ->where(
                $this
                    ->getEntityManager()
                    ->getExpressionBuilder()
                    ->isNull('a.publishedAt')
            )
            ->orderBy('a.repliedAt', Criteria::ASC)
            ->getQuery()
            ->getResult();

        return $results[0] ?? null;
    }

    /**
     * @throws ReplyStorageException
     */
    public function save(Reply $reply): void
    {
        try {
            $this->getEntityManager()->persist($reply);
            $this->getEntityManager()->flush();
        }
        catch (Exception $doctrineException) {
            throw new ReplyStorageException($doctrineException->getMessage(), $doctrineException);
        }
    }
}
