<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Domain;

interface ReplyPublisher
{
    /**
     * @throws ReplyPublicationException
     */
    public function publish(Reply $reply): void;
}
