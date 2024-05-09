<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Criteria;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\EqualTo;
use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression\Field;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;

final readonly class EqualsUsernameSpecification implements BotSpecification
{
    public function __construct(
        private Username $username,
    ) {}

    public function isSatisfiedBy(Bot $bot): bool
    {
        return $bot->username()->equals($this->username);
    }

    public function criteria(): Criteria
    {
        return new Criteria(
            expression: new EqualTo(
                field: new Field('username'),
                value: $this->username
            )
        );
    }
}
