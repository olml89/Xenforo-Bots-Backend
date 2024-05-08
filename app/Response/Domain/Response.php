<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Response\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Content\Domain\Content;

final readonly class Response
{
    public function __construct(
        private Uuid $responseId,
        private Bot $bot,
        private Content $content,
        private string $message,
    ) {}

    public function responseId(): Uuid
    {
        return $this->responseId;
    }

    public function bot(): Bot
    {
        return $this->bot;
    }

    public function content(): Content
    {
        return $this->content;
    }

    public function message(): string
    {
        return $this->message;
    }
}
