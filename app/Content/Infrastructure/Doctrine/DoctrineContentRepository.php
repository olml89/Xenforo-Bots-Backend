<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\XenforoBotsBackend\Content\Domain\AutoId;
use olml89\XenforoBotsBackend\Content\Domain\Content;
use olml89\XenforoBotsBackend\Content\Domain\ContentAlreadyExistsException;
use olml89\XenforoBotsBackend\Content\Domain\ContentRepository;
use olml89\XenforoBotsBackend\Content\Domain\ContentScope;
use olml89\XenforoBotsBackend\Content\Domain\ContentStorageException;
use Throwable;

final class DoctrineContentRepository extends EntityRepository implements ContentRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Content::class),
        );
    }

    public function getByExternalContentId(AutoId $externalContentId, ContentScope $scope): ?Content
    {
        return $this->findOneBy([
            'externalContentId' => $externalContentId,
            'scope' => $scope,
        ]);
    }

    /**
     * @throws ContentAlreadyExistsException
     * @throws ContentStorageException
     */
    public function save(Content $content): void
    {
        try {
            $this->getEntityManager()->persist($content);
            $this->getEntityManager()->flush();
        }
        catch (Throwable $doctrineException) {
            if ($doctrineException->getCode() === 1062) {
                throw ContentAlreadyExistsException::content($content, $doctrineException);
            }

            throw ContentStorageException::fromException($doctrineException);
        }
    }
}
