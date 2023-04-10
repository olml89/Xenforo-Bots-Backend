<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Infrastructure\Http\ProcessPost;

use Illuminate\Http\Response;
use olml89\XenforoBots\Answer\Application\Create\Post\CreateAnswersFromPostUseCase;
use olml89\XenforoBots\Common\Infrastructure\Laravel\Http\Responses\AcceptedResponse;

final class ProcessPostController
{
    public function __construct(
        private readonly CreateAnswersFromPostUseCase $createAnswerFromPost,
    ) {}

    public function __invoke(ProcessPostRequest $request): Response
    {
        $postData = $request->validated();
        $this->createAnswerFromPost->create($postData);

        return new AcceptedResponse();
    }
}
