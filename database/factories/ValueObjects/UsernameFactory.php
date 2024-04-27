<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;

final class UsernameFactory
{
    public function create(): Username
    {
        return Username::create(Str::random(50));
    }
}
