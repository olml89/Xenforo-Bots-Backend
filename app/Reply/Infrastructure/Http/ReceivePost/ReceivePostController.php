<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Reply\Infrastructure\Http\ReceivePost;

use Illuminate\Http\Response;
use olml89\XenforoBotsBackend\Reply\Application\Create\Post\CreateRepliesFromPostUseCase;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Http\Responses\AcceptedResponse;
use olml89\XenforoBotsBackend\Reply\Domain\ReplyStorageException;

final class ReceivePostController
{
    public function __construct(
        private readonly CreateRepliesFromPostUseCase $createRepliesFromPost,
    ) {}

    /**
     * @throws ReplyStorageException
     */
    public function __invoke(ReceivePostRequest $request): Response
    {
        $postData = $request->validated();
        $this->createRepliesFromPost->create($postData);

        return new AcceptedResponse();
    }
}
