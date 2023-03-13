<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Hasher;

use olml89\XenforoBots\Domain\ValueObjects\Password\Hasher as HasherContract;
use Illuminate\Contracts\Hashing\Hasher as LaravelHasherContract;

final class Hasher implements HasherContract
{
    public function __construct(
        private readonly LaravelHasherContract $laravelHasher,
    ) {}

    public function hash(string $password): string
    {
        return $this->laravelHasher->make($password);
    }

    public function check(string $password): bool
    {
        return $this->laravelHasher->check($password);
    }
}
