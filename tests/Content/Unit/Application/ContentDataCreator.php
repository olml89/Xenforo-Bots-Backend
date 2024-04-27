<?php declare(strict_types=1);

namespace Tests\Content\Unit\Application;

use Database\Factories\ContentFactory;
use olml89\XenforoBotsBackend\Content\Domain\Content;
use olml89\XenforoBotsBackend\Content\Infrastructure\Http\ContentData;

final class ContentDataCreator
{
    private ?int $content_id = null;
    private ?int $parent_content_id = null;
    private ?int $author_id = null;
    private ?string $author_name = null;
    private ?int $creation_date = null;
    private ?int $edition_date = null;
    private ?string $message = null;
    private ?Content $content = null;

    public function __construct(
        private readonly ContentFactory $contentFactory,
    ) {}

    public function contentId(int $content_id): self
    {
        $this->content_id = $content_id;

        return $this;
    }

    public function parentContentId(int $parent_content_id): self
    {
        $this->parent_content_id = $parent_content_id;

        return $this;
    }

    public function authorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function authorName(string $author_name): self
    {
        $this->author_name = $author_name;

        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function creationDate(int $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function editionDate(int $edition_date): self
    {
        $this->edition_date = $edition_date;

        return $this;
    }

    public function content(Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function reset(): void
    {
        $this->content_id = null;
        $this->parent_content_id = null;
        $this->author_id = null;
        $this->author_name = null;
        $this->creation_date = null;
        $this->edition_date = null;
        $this->message = null;
    }

    public function create(): ContentData
    {
        $this->content ??= $this->contentFactory->create();

        return new ContentData(
            content_id: $this->content_id ?? $this->content->externalContentId()->value(),
            parent_content_id: $this->parent_content_id ?? $this->content->externalParentContentId()->value(),
            author_id: $this->author_id ?? $this->content->author()->authorId()->value(),
            author_name: $this->author_name ?? (string)$this->content->author()->username(),
            creation_date: $this->creation_date ?? $this->content->createdAt()->timestamp(),
            edition_date: $this->edition_date ?? $this->content->editedAt()->timestamp(),
            message: $this->message ?? $this->content->message(),
        );
    }
}
