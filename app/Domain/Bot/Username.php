<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\Bot;

use olml89\XenforoBots\Domain\ValueObjects\StringValueObject;

final class Username extends StringValueObject
{
    public function __construct(string $username)
    {
        $this->ensureItHas50CharactersOrLess($username);

        parent::__construct($username);
    }

    private function ensureItHas50CharactersOrLess(string $username): void
    {
        if (strlen($username) > 50) {
            throw new InvalidUsernameException($username);
        }
    }

}
