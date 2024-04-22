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
            return new XenforoApiConnectionException($e);
        }

        return match ($e->getCode()) {
            Response::HTTP_NOT_FOUND => new XenforoApiNotFoundException($e),
            Response::HTTP_CONFLICT => new XenforoApiConflictException($e),
            Response::HTTP_UNPROCESSABLE_ENTITY => new XenforoApiUnprocessableEntityException($e),
            default => new XenforoApiInternalServerErrorException($e),
        };
    }
}
