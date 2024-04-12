<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

interface XenforoBotCreator
{
    public function create(string $username, string $password):
}
