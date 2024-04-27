<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;

final readonly class Author
{
    public function __construct(
        private AutoId $authorId,
        private Username $username,
    ) {}

    public function authorId(): AutoId
    {
        return $this->authorId;
    }

    public function username(): Username
    {
        return $this->username;
    }
}
