<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AcceptedResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(
            status: Response::HTTP_ACCEPTED,
        );
    }
}
