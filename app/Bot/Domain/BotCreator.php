<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface BotCreator
{
    /**
     * @throws BotValidationException
     * @throws BotCreationException
     */
    public function create(Username $username, Password $password): Bot;
}
