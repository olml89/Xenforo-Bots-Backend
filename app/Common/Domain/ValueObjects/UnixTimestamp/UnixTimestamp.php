<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp;

use DateTimeImmutable;

final readonly class UnixTimestamp
{
    private function __construct(
        private DateTimeImmutable $dateTime,
    ) {}

    /**
     * @throws InvalidUnixTimestampException
     */
    public static function create(int $timestamp): self
    {
        $dateTime = DateTimeImmutable::createFromFormat('U', (string)$timestamp);

        if (!$dateTime) {
            throw new InvalidUnixTimestampException($timestamp);
        }

        return new self($dateTime);
    }

    public function value(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
