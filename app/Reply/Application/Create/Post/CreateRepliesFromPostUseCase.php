<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Application\Create\Post;

use olml89\XenforoBots\Reply\Domain\Reply;
use olml89\XenforoBots\Reply\Domain\ReplyRepository;
use olml89\XenforoBots\Reply\Domain\ReplyStorageException;
use olml89\XenforoBots\Reply\Domain\ContentType;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;

final class CreateRepliesFromPostUseCase
{
    public function __construct(
        private readonly BotRepository $botRepository,
        private readonly UuidManager $uuidManager,
        private readonly ReplyRepository $replyRepository,
    ) {}

    /**
     * @throws ReplyStorageException
     */
    public function create(PostData $postData): void
    {
        $subscribedBots = $this->botRepository->allSubscribed();

        foreach($subscribedBots as $bot) {
            $reply = new Reply(
                id: $this->uuidManager->random(),
                type: ContentType::POST,
                contentId: new AutoId($postData->post_id),
                containerId: new AutoId($postData->thread_id),
                content: $postData->message,
                bot: $bot,
            );

            $this->replyRepository->save($reply);
        }
    }
}
