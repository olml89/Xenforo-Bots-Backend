<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Reply\Domain;

interface ReplyPublisher
{
    /**
     * @throws ReplyPublicationException
     */
    public function publish(Reply $reply): void;
}
