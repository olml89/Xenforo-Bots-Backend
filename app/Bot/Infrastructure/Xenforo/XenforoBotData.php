<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiResponseData;
use Psr\Http\Message\ResponseInterface;

final readonly class XenforoBotData extends ApiResponseData
{
    public function __construct(
        public string $bot_id,
        public string $api_key,
        public int $user_id,
        public int $created_at,
    ) {}

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);
        $bot = $json['bot'];

        return new self(
            bot_id: $bot['bot_id'],
            api_key: $bot['ApiKey']['api_key'],
            user_id: $bot['user_id'],
            created_at: $bot['created_at'],
        );
    }
}
