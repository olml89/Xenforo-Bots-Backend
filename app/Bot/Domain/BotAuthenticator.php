<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface BotAuthenticator
{
    /**
     * @throws BotCreationException
     */
    public function authenticate(Username $username, string $password): Bot;
}
