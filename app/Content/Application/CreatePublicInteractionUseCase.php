<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Application;

use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Content\Domain\ContentAlreadyExistsException;
use olml89\XenforoBotsBackend\Content\Domain\ContentCreator;
use olml89\XenforoBotsBackend\Content\Domain\ContentRepository;
use olml89\XenforoBotsBackend\Content\Domain\ContentStorageException;
use olml89\XenforoBotsBackend\Content\Domain\ContentValidationException;
use olml89\XenforoBotsBackend\Content\Infrastructure\Http\ContentData;

final readonly class CreatePublicInteractionUseCase
{
    public function __construct(
        private BotFinder $botFinder,
        private ContentCreator $contentCreator,
        private ContentRepository $contentRepository,
    ) {}

    /**
     * @throws ContentValidationException
     * @throws BotNotFoundException
     * @throws ContentAlreadyExistsException
     * @throws ContentStorageException
     */
    public function create(string $botId, ContentData $contentData): void
    {
        try {
            $botId = Uuid::create($botId);
            $bot = $this->botFinder->find($botId);
            $content = $this->contentCreator->create($contentData);
            $this->contentRepository->save($content);
        }
        catch (ValueObjectException $e) {
            throw ContentValidationException::fromException($e);
        }
    }
}
