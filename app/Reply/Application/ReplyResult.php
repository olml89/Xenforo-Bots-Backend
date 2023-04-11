<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Application;

use olml89\XenforoBots\Reply\Domain\Reply;
use olml89\XenforoBots\Bot\Application\BotResult;

final class ReplyResult
{
    public readonly string $id;
    public readonly string $type;
    public readonly int $content_id;
    public readonly int $container_id;
    public readonly string $response;
    public readonly BotResult $bot;
    public readonly string $replied_at;
    public readonly ?string $published_at;

    public function __construct(Reply $reply)
    {
        $this->id = (string)$reply->id();
        $this->type = $reply->getType()->value;
        $this->content_id = $reply->contentId()->toInt();
        $this->container_id = $reply->containerId()->toInt();
        $this->response = $reply->getResponse();
        $this->bot = new BotResult($reply->bot());
        $this->replied_at = $reply->repliedAt()->format('c');
        $this->published_at = $reply->isPublished()
            ? $reply->publishedAt()->format('c')
            : null;
    }
}
