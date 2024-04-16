<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;

final class XenforoApiExceptionMapper
{
    public function map(GuzzleException $e): XenforoApiException
    {
        if (!($e instanceof RequestException)) {
            return XenforoApiConnectionException::create($e);
        }

        return match ($e->getCode()) {
            Response::HTTP_UNPROCESSABLE_ENTITY => XenforoApiUnprocessableEntityException::create($e),
            default => XenforoApiInternalServerErrorException::create($e),
        };
    }
}
