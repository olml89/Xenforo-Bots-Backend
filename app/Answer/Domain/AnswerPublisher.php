<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Domain;

interface AnswerPublisher
{
    /**
     * @throws AnswerPublicationException
     */
    public function publish(Answer $answer): void;
}
