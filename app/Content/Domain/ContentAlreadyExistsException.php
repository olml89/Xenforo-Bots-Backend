<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityAlreadyExistsException;
use Throwable;

final class ContentAlreadyExistsException extends EntityAlreadyExistsException
{
    public static function content(Content $content, Throwable $doctrineException): self
    {
        return new self(
            message: sprintf(
                'Content of %s scope already exists (id: %s)',
                $content->scope()->value,
                $content->externalContentId()
            ),
            previous: $doctrineException,
        );
    }
}
