<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\AutoIdFactory;
use Database\Factories\ValueObjects\UnixTimestampFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use Database\Factories\ValueObjects\UuidFactory;
use Illuminate\Support\Arr;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Content\Domain\Author;
use olml89\XenforoBotsBackend\Content\Domain\AutoId;
use olml89\XenforoBotsBackend\Content\Domain\Content;
use olml89\XenforoBotsBackend\Content\Domain\ContentScope;

final class ContentFactory
{
    private ?Uuid $contentId = null;
    private ?ContentScope $scope = null;
    private ?AutoId $externalContentId = null;
    private ?AutoId $parentExternalContentId = null;
    private ?Author $author = null;
    private ?string $message = null;
    private ?UnixTimestamp $createdAt = null;
    private ?UnixTimestamp $editedAt = null;

    public function __construct(
        private readonly UuidFactory $uuidFactory,
        private readonly AutoIdFactory $autoIdFactory,
        private readonly UsernameFactory $usernameFactory,
        private readonly UnixTimestampFactory $unixTimestampFactory,
    ) {}

    public function contentId(Uuid $contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    public function scope(ContentScope $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    public function externalContentId(AutoId $externalContentId): self
    {
        $this->externalContentId = $externalContentId;

        return $this;
    }

    public function parentExternalContentId(AutoId $parentExternalContentId): self
    {
        $this->parentExternalContentId = $parentExternalContentId;

        return $this;
    }

    public function author(Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function createdAt(UnixTimestamp $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function editedAt(UnixTimestamp $editedAt): self
    {
        $this->editedAt = $editedAt;

        return $this;
    }

    public function reset(): void
    {
        $this->contentId = null;
        $this->scope = null;
        $this->externalContentId = null;
        $this->parentExternalContentId = null;
        $this->author = null;
        $this->message = null;
        $this->createdAt = null;
        $this->editedAt = null;
    }

    public function create(): Content
    {
        return new Content(
            contentId: $this->contentId ?? $this->uuidFactory->create(),
            scope: $this->scope ?? Arr::random(ContentScope::cases()),
            externalContentId: $this->externalContentId ?? $this->autoIdFactory->create(),
            externalParentContentId: $this->parentExternalContentId ?? $this->autoIdFactory->create(),
            author: $this->author ?? new Author(
                authorId: $this->autoIdFactory->create(),
                username: $this->usernameFactory->create()
            ),
            message: $this->message ?? fake()->text(),
            createdAt: $this->createdAt ?? $this->unixTimestampFactory->create(),
            editedAt: $this->editedAt ?? $this->unixTimestampFactory->create()
        );
    }
}
