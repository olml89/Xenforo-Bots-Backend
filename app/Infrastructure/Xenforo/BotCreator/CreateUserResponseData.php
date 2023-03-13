<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Xenforo\BotCreator;

use olml89\XenforoBots\Infrastructure\Xenforo\ApiResponseData;
use Psr\Http\Message\ResponseInterface;

final class CreateUserResponseData extends ApiResponseData
{
    private function __construct(
        public readonly int $user_id,
        public readonly int $register_date,
    ) {}

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);
        $user = $json['user'];

        return new self(
            user_id: $user['user_id'],
            register_date: $user['register_date'],
        );
    }
}
