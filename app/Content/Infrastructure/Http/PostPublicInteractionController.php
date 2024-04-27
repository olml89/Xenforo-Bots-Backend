<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Infrastructure\Http;

use Illuminate\Http\JsonResponse;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Http\Responses\AcceptedResponse;
use olml89\XenforoBotsBackend\Content\Application\CreatePublicInteractionUseCase;
use olml89\XenforoBotsBackend\Content\Domain\ContentAlreadyExistsException;
use olml89\XenforoBotsBackend\Content\Domain\ContentStorageException;
use olml89\XenforoBotsBackend\Content\Domain\ContentValidationException;

final readonly class PostPublicInteractionController
{
    public function __construct(
        private CreatePublicInteractionUseCase $createPublicInteraction,
    ) {}

    /**
     * @throws ContentValidationException
     * @throws BotNotFoundException
     * @throws ContentAlreadyExistsException
     * @throws ContentStorageException
     */
    public function __invoke(string $botId, CreateContentRequest $createContentRequest): JsonResponse
    {
        $this->createPublicInteraction->create(
            $botId,
            $createContentRequest->contentData()
        );

        return new AcceptedResponse();
    }
}
