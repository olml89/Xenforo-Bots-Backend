<?php declare(strict_types=1);

namespace olml89\XenforoBots\Answer\Infrastructure\AnswerPublisher;

use olml89\XenforoBots\Answer\Domain\Answer;
use olml89\XenforoBots\Answer\Domain\AnswerPublicationException;
use olml89\XenforoBots\Answer\Domain\AnswerPublisher;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Post\RequestData as PostRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApiException;

final class XenforoPostPublisher implements AnswerPublisher
{
    public function __construct(
        private readonly XenforoApi $xenforoApi,
    ) {}

    /**
     * @throws AnswerPublicationException
     */
    public function publish(Answer $answer): void
    {
        try {
            $postRequestData = new PostRequestData(
                thread_id: $answer->containerId()->toInt(),
                message: $answer->getResponse(),
            );

            $postResponseData = $this->xenforoApi->postPost(
                user_id: $answer->bot()->userId()->toInt(),
                postRequestData: $postRequestData,
            );

            $answer->deliver(
                UnixTimestamp::toDateTimeImmutable($postResponseData->post_date)
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new AnswerPublicationException($e->getMessage(), $e);
        }
    }
}
