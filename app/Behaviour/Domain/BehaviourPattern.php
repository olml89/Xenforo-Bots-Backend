<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain;

use olml89\XenforoBotsBackend\Content\Domain\Content;

interface BehaviourPattern
{
    public function reactTo(Content $content, string $processedMessage): string;
}
