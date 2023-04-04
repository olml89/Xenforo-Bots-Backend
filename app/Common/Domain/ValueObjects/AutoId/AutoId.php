<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects\AutoId;

use olml89\XenforoBots\Common\Domain\ValueObjects\IntValueObject;

final class AutoId extends IntValueObject
{
    public function __construct(int $auto_id)
    {
        $this->ensureAutoIdIsBiggerThan0($auto_id);

        parent::__construct($auto_id);
    }

    private function ensureAutoIdIsBiggerThan0(int $auto_id): void
    {
        if ($auto_id <= 0) {
            throw new InvalidAutoIdException($auto_id);
        }
    }
}
