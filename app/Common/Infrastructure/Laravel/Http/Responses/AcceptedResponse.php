<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Laravel\Http\Responses;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class AcceptedResponse extends Response
{
    public function __construct(array $headers = [])
    {
        parent::__construct(
            content: null,
            status: SymfonyResponse::HTTP_ACCEPTED,
            headers: $headers,
        );
    }
}
