<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPatterns;

use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPattern;
use olml89\XenforoBotsBackend\Content\Domain\Content;

final class RepeatBackwards implements BehaviourPattern
{
    public function reactTo(Content $content, string $processedMessage): string
    {
        return strrev($processedMessage);
    }
}
