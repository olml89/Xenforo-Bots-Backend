<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints;

use GuzzleHttp\Handler\MockHandler;

abstract readonly class Resource
{
    public function __construct(
        protected MockHandler $responses,
    ) {}
}
