<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Domain;

use Exception;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use Throwable;

final class SubscriptionRemovalException extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }

    public static function notSubscribed(Bot $bot): self
    {
        return new self(
            sprintf(
                'Bot <%s> is not subscribed',
                $bot->id(),
            )
        );
    }
}
