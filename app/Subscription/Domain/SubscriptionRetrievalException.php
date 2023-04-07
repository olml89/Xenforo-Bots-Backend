<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Domain;

use Exception;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\Url;
use Throwable;

final class SubscriptionRetrievalException extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }

    public static function xenforoError(Url $xenforoUrl, int $httpStatusCode): self
    {
        return new self(
            sprintf('Response with status %s returned from \'%s\'', $httpStatusCode, $xenforoUrl)
        );
    }
}
