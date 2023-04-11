<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Domain;

use Exception;
use Throwable;

final class ReplyPublicationException extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }
}
