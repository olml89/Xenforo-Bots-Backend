<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Domain;

use DateTimeImmutable;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;

final class Reply
{
    private readonly DateTimeImmutable $repliedAt;
    private ?DateTimeImmutable $publishedAt;

    public function __construct(
        private readonly Uuid $id,
        private readonly ContentType $type,
        private readonly AutoId $contentId,
        private readonly AutoId $containerId,
        private readonly string $response,
        private readonly Bot $bot,
    ) {
        $this->repliedAt = new DateTimeImmutable();
        $this->publishedAt = null;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function getType(): ContentType
    {
        return $this->type;
    }

    public function contentId(): AutoId
    {
        return $this->contentId;
    }

    public function containerId(): AutoId
    {
        return $this->containerId;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function bot(): Bot
    {
        return $this->bot;
    }

    public function repliedAt(): DateTimeImmutable
    {
        return $this->repliedAt;
    }

    public function isPublished(): bool
    {
        return !is_null($this->publishedAt);
    }

    public function publishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function publish(DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
