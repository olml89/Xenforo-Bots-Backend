<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface BotAuthenticator
{
    /**
     * @throws BotValidationException
     */
    public function authenticate(Username $username, string $password): Bot;
}
