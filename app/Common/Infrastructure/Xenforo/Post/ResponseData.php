<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Post;

use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiResponseData;
use Psr\Http\Message\ResponseInterface;

final class ResponseData extends ApiResponseData
{
    private function __construct(
        public readonly int $post_id,
        public readonly int $post_date,
    ) {}

    public static function fromResponse(ResponseInterface $response): ApiResponseData
    {
        $json = self::jsonDecode($response);
        $post = $json['post'];

        return new self(
            post_id: $post['post_id'],
            post_date: $post['post_date'],
        );
    }
}
