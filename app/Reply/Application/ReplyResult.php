<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Reply\Application;

use olml89\XenforoBotsBackend\Reply\Domain\Reply;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;

final class ReplyResult
{
    public readonly string $id;
    public readonly string $type;
    public readonly int $content_id;
    public readonly int $container_id;
    public readonly string $content;
    public readonly ?string $response;
    public readonly BotResult $bot;
    public readonly string $created_at;
    public readonly string $processed_at;
    public readonly ?string $published_at;

    public function __construct(Reply $reply)
    {
        $this->id = (string)$reply->id();
        $this->type = $reply->getType()->value;
        $this->content_id = $reply->contentId()->toInt();
        $this->container_id = $reply->containerId()->toInt();
        $this->content = $reply->getContent();
        $this->response = $reply->getResponse();
        $this->bot = new BotResult($reply->bot());
        $this->created_at = $reply->createdAt()->format('c');
        $this->processed_at = $reply->isProcessed()
            ? $reply->processedAt()->format('c')
            : null;
        $this->published_at = $reply->isPublished()
            ? $reply->publishedAt()->format('c')
            : null;
    }
}
