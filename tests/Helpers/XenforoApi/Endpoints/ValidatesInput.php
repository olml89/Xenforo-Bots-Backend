<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ValidatesInput
{
    public function unprocessableEntityException(string $errorCode, string $errorMessage, array $params = []): RequestException
    {
        $this->responses->append(
            $unprocessableEntityException = new RequestException(
                message: '',
                request: $this->request,
                response: new Response(
                    status: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
                    body: json_encode([
                        'errors' => [
                            [
                                'code' => $errorCode,
                                'message' => $errorMessage,
                                'params' => $params,
                            ],
                        ],
                    ]),
                )
            )
        );

        return $unprocessableEntityException;
    }
}
