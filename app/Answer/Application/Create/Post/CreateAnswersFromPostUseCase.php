<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Application\Create\Post;

use olml89\XenforoBots\Answer\Domain\Answer;
use olml89\XenforoBots\Answer\Domain\AnswerRepository;
use olml89\XenforoBots\Answer\Domain\ContentType;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;

final class CreateAnswersFromPostUseCase
{
    public function __construct(
        private readonly BotRepository $botRepository,
        private readonly UuidManager $uuidManager,
        private readonly AnswerRepository $answerRepository,
    ) {}

    public function create(PostData $postData): void
    {
        $subscribedBots = $this->botRepository->allSubscribed();

        foreach($subscribedBots as $bot) {
            $response = $bot->answer($postData->message);

            $answer = new Answer(
                id: $this->uuidManager->random(),
                bot: $bot,
                parentId: new AutoId($postData->thread_id),
                type: ContentType::POST,
                content: $response,
            );

            $this->answerRepository->save($answer);
        }
    }
}
