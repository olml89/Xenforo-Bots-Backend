<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Password;

final class PasswordFactory
{
    public function create(): Password
    {
        return Password::create(Str::password());
    }
}
