<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Response\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\XenforoBotsBackend\Content\Domain\Content;

final readonly class ResponseCreator
{
    public function __construct(
        private UuidGenerator $uuidGenerator,
    ) {}

    public function create(Bot $bot, Content $content, string $message): Response
    {
        return new Response(
            responseId: $this->uuidGenerator->generate(),
            bot: $bot,
            content: $content,
            message: $message
        );
    }
}
