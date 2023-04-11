<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Domain;

use DateTimeImmutable;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;

final class Answer
{
    private readonly DateTimeImmutable $answeredAt;
    private ?DateTimeImmutable $deliveredAt;

    public function __construct(
        private readonly Uuid $id,
        private readonly ContentType $type,
        private readonly AutoId $contentId,
        private readonly AutoId $containerId,
        private readonly string $response,
        private readonly Bot $bot,
    ) {
        $this->answeredAt = new DateTimeImmutable();
        $this->deliveredAt = null;
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

    public function answeredAt(): DateTimeImmutable
    {
        return $this->answeredAt;
    }

    public function isDelivered(): bool
    {
        return !is_null($this->deliveredAt);
    }

    public function deliveredAt(): ?DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function deliver(DateTimeImmutable $deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }
}
