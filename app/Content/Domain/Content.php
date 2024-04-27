<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final readonly class Content
{
    public function __construct(
        private Uuid $contentId,
        private ContentScope  $scope,
        private AutoId $externalContentId,
        private AutoId $externalParentContentId,
        private Author $author,
        private string $message,
        private UnixTimestamp $createdAt,
        private UnixTimestamp $editedAt,
    ) {}

    public function contentId(): Uuid
    {
        return $this->contentId;
    }

    public function scope(): ContentScope
    {
        return $this->scope;
    }

    public function hasScope(ContentScope $scope): bool
    {
        return $this->scope === $scope;
    }

    public function externalContentId(): AutoId
    {
        return $this->externalContentId;
    }

    public function externalParentContentId(): AutoId
    {
        return $this->externalParentContentId;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function createdAt(): UnixTimestamp
    {
        return $this->createdAt;
    }

    public function editedAt(): UnixTimestamp
    {
        return $this->editedAt;
    }
}
