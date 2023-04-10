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
        private readonly Bot $bot,
        private readonly AutoId $parentId,
        private readonly ContentType $type,
        private readonly string $content,
    ) {
        $this->answeredAt = new DateTimeImmutable();
        $this->deliveredAt = null;
    }

    public function deliver(): self
    {
        $this->deliveredAt = new DateTimeImmutable();

        return $this;
    }
}
