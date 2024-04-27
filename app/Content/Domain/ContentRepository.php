<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

interface ContentRepository
{
    public function getByExternalContentId(AutoId $externalContentId, ContentScope $scope): ?Content;

    /**
     * @throws ContentAlreadyExistsException
     * @throws ContentStorageException
     */
    public function save(Content $content): void;
}
