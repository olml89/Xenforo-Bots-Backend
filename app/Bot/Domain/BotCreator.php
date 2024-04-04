<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface BotCreator
{
    /**
     * @throws BotCreationException
     */
    public function create(string $name, string $password): Bot;
}
