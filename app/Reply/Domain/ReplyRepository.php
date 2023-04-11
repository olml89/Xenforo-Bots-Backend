<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Domain;

interface ReplyRepository
{
    public function getNextDeliverable(): ?Reply;

    /**
     * @throws ReplyStorageException
     */
    public function save(Reply $reply): void;
}
