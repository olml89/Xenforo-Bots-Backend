<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Domain;

interface AnswerRepository
{
    public function getNextDeliverable(): ?Answer;

    /**
     * @throws AnswerStorageException
     */
    public function save(Answer $answer): void;
}
