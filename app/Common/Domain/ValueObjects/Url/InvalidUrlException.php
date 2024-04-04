<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidUrlException extends ValueObjectException
{
    public function __construct(string $url)
    {
        parent::__construct(
            sprintf(
                'Must represent a valid url, <%s> provided',
                $url,
            )
        );
    }
}
