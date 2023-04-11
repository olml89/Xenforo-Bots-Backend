<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Domain;

use InvalidArgumentException;

final class ReplyPublisherManager
{
    /**
     * @var array<ContentType, ReplyPublisher>
     */
    private array $replyPublishers = [];

    public function get(ContentType $type): ReplyPublisher
    {
        if (!array_key_exists($type->value, $this->replyPublishers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Reply publisher for the type <%s> is not implemented',
                    $type->value
                )
            );
        }

        return $this->replyPublishers[$type->value];
    }

    public function add(ContentType $type, ReplyPublisher $replyPublisher): self
    {
        $this->replyPublishers[$type->value] = $replyPublisher;

        return $this;
    }
}
