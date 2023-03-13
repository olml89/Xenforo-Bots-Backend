<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\Password;

use olml89\XenforoBots\Domain\ValueObjects\StringValueObject;

final class Password extends StringValueObject
{
    private readonly Hasher $hasher;

    public function __construct(string $password, Hasher $hasher)
    {
        $this->hasher = $hasher;

        parent::__construct($this->hasher->hash($password));
    }

    public function check($password): bool
    {
        return $this->hasher->check($password);
    }
}
