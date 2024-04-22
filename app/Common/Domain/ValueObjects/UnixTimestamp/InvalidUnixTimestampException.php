<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidUnixTimestampException extends ValueObjectException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function format(string $format, string $dateTimeString): self
    {
        return new self(
            sprintf(
                'Must represent a valid DateTime in the \'%s\' format, \'%s\' provided',
                $format,
                $dateTimeString,
            )
        );
    }

    public static function invalid(): self
    {
        return new self('Must represent a valid DateTime');
    }
}
