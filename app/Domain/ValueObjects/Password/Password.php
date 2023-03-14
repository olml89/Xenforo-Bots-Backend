<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\Password;

final class Password
{
    private readonly string $hash;

    public function __construct(string $password, Hasher $hasher)
    {
        $this->hash = $hasher->hash($password);
    }

    public function check($password, Hasher $hasher): bool
    {
        return $hasher->check($password, $this->hash);
    }
}
