<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Http\Middleware;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class EnsurePlatformApiKeyIsValid
{
    private const string PLATFORM_API_KEY = 'Platform-Api-Key';

    public function __construct(
        private readonly Repository $config,
    ) {}

    public function handle(Request $request, Closure $next): JsonResponse
    {
        $platformApiKeyValue = $request->header('Platform-Api-Key');

        if (is_null($platformApiKeyValue)) {
            throw new UnauthorizedHttpException(
                challenge: '',
                message: sprintf(
                    '%s header is not set',
                    self::PLATFORM_API_KEY,
                )
            );
        }

        if ($platformApiKeyValue !== $this->config->get('app.api_key')) {
            throw new UnauthorizedHttpException(
                challenge: '',
                message: sprintf(
                    '%s header is not valid',
                    self::PLATFORM_API_KEY,
                )
            );
        }

        return $next($request);
    }
}
