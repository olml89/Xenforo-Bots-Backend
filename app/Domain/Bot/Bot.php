<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\Bot;

use olml89\XenforoBots\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Domain\ValueObjects\Uuid\Uuid;

final class Bot
{
    public function __construct(
        private readonly Uuid $id,
        private readonly AutoId $userId,
        private readonly Username $name,
        private readonly Password $password,
        private readonly UnixTimestamp $registeredAt,
    ) {}

    public function id(): Uuid
    {
        return $this->id;
    }

    public function userId(): AutoId
    {
        return $this->userId;
    }

    public function name(): Username
    {
        return $this->name;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function registeredAt(): UnixTimestamp
    {
        return $this->registeredAt;
    }
}
