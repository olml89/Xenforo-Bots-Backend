<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Application\Send;

use olml89\XenforoBots\Answer\Application\AnswerResult;
use olml89\XenforoBots\Answer\Domain\AnswerPublicationException;
use olml89\XenforoBots\Answer\Domain\AnswerPublisherManager;
use olml89\XenforoBots\Answer\Domain\AnswerRepository;
use olml89\XenforoBots\Answer\Domain\AnswerStorageException;

final class SendAnswerUseCase
{
    public function __construct(
        private readonly AnswerRepository $answerRepository,
        private readonly AnswerPublisherManager $answerPublisherManager,
    ) {}

    /**
     * @throws AnswerPublicationException | AnswerStorageException
     */
    public function send(): ?AnswerResult
    {
        $answer = $this->answerRepository->getNextDeliverable();

        if (!$answer) {
            return null;
        }

        $this->answerPublisherManager->get($answer->getType())->publish($answer);
        $this->answerRepository->save($answer);

        return new AnswerResult($answer);
    }
}
