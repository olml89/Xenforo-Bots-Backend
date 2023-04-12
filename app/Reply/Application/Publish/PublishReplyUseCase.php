<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Application\Publish;

use olml89\XenforoBots\Reply\Application\ReplyResult;
use olml89\XenforoBots\Reply\Domain\ReplyPublicationException;
use olml89\XenforoBots\Reply\Domain\ReplyPublisherManager;
use olml89\XenforoBots\Reply\Domain\ReplyRepository;
use olml89\XenforoBots\Reply\Domain\ReplyStorageException;

final class PublishReplyUseCase
{
    public function __construct(
        private readonly ReplyRepository $replyRepository,
        private readonly ReplyPublisherManager $replyPublisherManager,
    ) {}

    /**
     * @throws ReplyPublicationException | ReplyStorageException
     */
    public function publish(): ?ReplyResult
    {
        $reply = $this->replyRepository->getNextDeliverable();

        if (!$reply) {
            return null;
        }

        $this->replyPublisherManager->get($reply->getType())->publish($reply);
        $this->replyRepository->save($reply);

        return new ReplyResult($reply);
    }
}
