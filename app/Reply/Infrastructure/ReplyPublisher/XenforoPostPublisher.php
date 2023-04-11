<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Infrastructure\ReplyPublisher;

use olml89\XenforoBots\Reply\Domain\Reply;
use olml89\XenforoBots\Reply\Domain\ReplyPublicationException;
use olml89\XenforoBots\Reply\Domain\ReplyPublisher;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\Post\RequestData as PostRequestData;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApiException;

final class XenforoPostPublisher implements ReplyPublisher
{
    public function __construct(
        private readonly XenforoApi $xenforoApi,
    ) {}

    /**
     * @throws ReplyPublicationException
     */
    public function publish(Reply $reply): void
    {
        try {
            $postRequestData = new PostRequestData(
                thread_id: $reply->containerId()->toInt(),
                message: $reply->getResponse(),
            );

            $postResponseData = $this->xenforoApi->postPost(
                user_id: $reply->bot()->userId()->toInt(),
                postRequestData: $postRequestData,
            );

            $reply->publish(
                UnixTimestamp::toDateTimeImmutable($postResponseData->post_date)
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new ReplyPublicationException($e->getMessage(), $e);
        }
    }
}
