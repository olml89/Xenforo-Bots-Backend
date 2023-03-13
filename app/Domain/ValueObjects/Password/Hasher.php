<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\Password;

interface Hasher
{
    public function hash(string $password): string;
    public function check(string $password): bool;
}
