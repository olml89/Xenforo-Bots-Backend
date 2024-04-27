<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;

interface BotProvider
{
    /**
     * @throws BotValidationException
     * @throws BotProvisionException
     */
    public function provide(Username $username, Password $password): Bot;
}
