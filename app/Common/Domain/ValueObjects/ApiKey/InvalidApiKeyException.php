<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidApiKeyException extends ValueObjectException
{
    public function __construct(string $api_key)
    {
        parent::__construct(
            sprintf(
                'Api key has to be a string of 32 characters, \'%s\' provided with %s characters',
                $api_key,
                strlen($api_key),
            )
        );
    }
}
