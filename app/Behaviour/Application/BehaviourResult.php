<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Application;

use olml89\XenforoBotsBackend\Behaviour\Domain\Behaviour;
use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class BehaviourResult extends JsonSerializable
{
    public readonly string $behaviour_id;
    public readonly string $name;
    public readonly string $pattern;

    public function __construct(Behaviour $behaviour)
    {
        $this->behaviour_id = (string)$behaviour->behaviourId();
        $this->name = (string)$behaviour->behaviourName();
        $this->pattern = $behaviour->behaviourPattern()::class;
    }
}
