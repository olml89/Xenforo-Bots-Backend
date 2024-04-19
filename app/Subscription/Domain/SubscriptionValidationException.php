<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Domain;

use Exception;
use Throwable;

final class SubscriptionValidationException extends Exception
{
    public function __construct(Throwable $exception)
    {
        parent::__construct(
            message: $exception->getMessage(),
            previous: $exception,
        );
    }
}
