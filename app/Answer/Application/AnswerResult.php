<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Application;

use olml89\XenforoBots\Answer\Domain\Answer;
use olml89\XenforoBots\Bot\Application\BotResult;

final class AnswerResult
{
    public readonly string $id;
    public readonly string $type;
    public readonly int $content_id;
    public readonly int $container_id;
    public readonly string $response;
    public readonly BotResult $bot;
    public readonly string $answered_at;
    public readonly ?string $delivered_at;

    public function __construct(Answer $answer)
    {
        $this->id = (string)$answer->id();
        $this->type = $answer->getType()->value;
        $this->content_id = $answer->contentId()->toInt();
        $this->container_id = $answer->containerId()->toInt();
        $this->response = $answer->getResponse();
        $this->bot = new BotResult($answer->bot());
        $this->answered_at = $answer->answeredAt()->format('c');
        $this->delivered_at = $answer->isDelivered()
            ? $answer->deliveredAt()->format('c')
            : null;
    }
}
