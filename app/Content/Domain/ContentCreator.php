<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Content\Infrastructure\Http\ContentData;

final readonly class ContentCreator
{
    public function __construct(
        private UuidGenerator $uuidGenerator,
    ) {}

    /**
     * @throws ContentValidationException
     */
    public function create(ContentData $contentData): Content
    {
        try {
            return new Content(
                contentId: $this->uuidGenerator->generate(),
                scope: ContentScope::public,
                externalContentId: AutoId::create($contentData->content_id),
                externalParentContentId: AutoId::create($contentData->parent_content_id),
                author: new Author(
                    authorId: AutoId::create($contentData->author_id),
                    username: Username::create($contentData->author_name)
                ),
                message: $contentData->message,
                createdAt: UnixTimestamp::create($contentData->creation_date),
                editedAt: UnixTimestamp::create($contentData->edition_date)
            );
        }
        catch (ValueObjectException $e) {
            throw ContentValidationException::fromException($e);
        }
    }
}
