<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

interface ContentRepository
{
    public function getOneBy(ContentSpecification $specification): ?Content;

    /**
     * @throws ContentAlreadyExistsException
     * @throws ContentStorageException
     */
    public function save(Content $content): void;
}
