<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Infrastructure\Persistence;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\Expr\OrderBy;
use olml89\XenforoBots\Answer\Domain\Answer;
use olml89\XenforoBots\Answer\Domain\AnswerRepository;
use olml89\XenforoBots\Answer\Domain\AnswerStorageException;
use olml89\XenforoBots\Bot\Domain\Bot;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

final class DoctrineAnswerRepository extends EntityRepository implements AnswerRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Answer::class),
        );
    }

    public function getNextDeliverable(): ?Answer
    {
        /**
         * Reason to use DQL instead of findOneBy:
         * https://github.com/doctrine/orm/issues/9505
         *
         * Answer -> Bot -> Subscription
         *
         * If Bot is set as EAGER loading it doesn't try to update the uuid when getting $answer->bot(),
         * but because Subscription is on a 2nd grade of inheritance, even though is set as EAGER loading,
         * it will always be a proxy if we do $answer->bot()->subscription() (but not if we get a Bot
         * through BotRepository and do $bot->subscription()).
         *
         * So we have to mount the entities manually through DQL, joining the relationships is like eager-loading
         * them.
         */
        $results = $this
            ->getEntityManager()
            ->getRepository(Answer::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.bot', 'b')
            ->leftJoin('b.subscription', 's')
            ->select('a, b, s')
            ->where(
                $this
                    ->getEntityManager()
                    ->getExpressionBuilder()
                    ->isNull('a.deliveredAt')
            )
            ->orderBy('a.answeredAt', Criteria::ASC)
            ->getQuery()
            ->getResult();

        return $results[0] ?? null;
    }

    /**
     * @throws AnswerStorageException
     */
    public function save(Answer $answer): void
    {
        try {
            $this->getEntityManager()->persist($answer);
            $this->getEntityManager()->flush();
        }
        catch (Exception $doctrineException) {
            throw new AnswerStorageException($doctrineException->getMessage(), $doctrineException);
        }
    }
}
