<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Infrastructure\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class PostContentController
{
    public function __invoke(string $botId, CreateContentRequest $createContentRequest): JsonResponse
    {
        $b = 2;
        $c = 3;
    }
}
