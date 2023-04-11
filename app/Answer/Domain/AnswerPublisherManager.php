<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Domain;

use InvalidArgumentException;

final class AnswerPublisherManager
{
    /**
     * @var array<ContentType, AnswerPublisher>
     */
    private array $answerPublishers = [];

    public function get(ContentType $type): AnswerPublisher
    {
        if (!array_key_exists($type->value, $this->answerPublishers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Answer publisher for the type <%s> is not implemented',
                    $type->value
                )
            );
        }

        return $this->answerPublishers[$type->value];
    }

    public function add(ContentType $type, AnswerPublisher $answerPublisher): self
    {
        $this->answerPublishers[$type->value] = $answerPublisher;

        return $this;
    }
}
