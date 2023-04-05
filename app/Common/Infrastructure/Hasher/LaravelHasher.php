<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Hasher;

use Illuminate\Contracts\Hashing\Hasher as LaravelHasherContract;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher as HasherContract;

final class LaravelHasher implements HasherContract
{
    public function __construct(
        private readonly LaravelHasherContract $laravelHasher,
    ) {}

    public function hash(string $password): string
    {
        return $this->laravelHasher->make($password);
    }

    public function check(string $password, string $hash): bool
    {
        return $this->laravelHasher->check($password, $hash);
    }
}
