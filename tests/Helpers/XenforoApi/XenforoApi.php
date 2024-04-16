<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi;

use GuzzleHttp\HandlerStack;
use Illuminate\Foundation\Application;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumerFactory;

trait XenforoApi
{
    private readonly XenforoApiResponseSimulator $xenforoApiResponseSimulator;

    public function setUpXenforoApiConsumer(): void
    {
        if (!isset($this->xenforoApiResponseSimulator)) {
            $this->xenforoApiResponseSimulator = $this->resolve(XenforoApiResponseSimulator::class);
        }

        $this->app->singleton(
            XenforoApiConsumer::class,
            function (Application $app): XenforoApiConsumer {
                /** @var XenforoApiConsumerFactory $xenforoApiConsumerFactory */
                $xenforoApiConsumerFactory = $app->get(XenforoApiConsumerFactory::class);

                return $xenforoApiConsumerFactory->create(
                    handlerStack:  HandlerStack::create($this->xenforoApiResponseSimulator->responses())
                );
            }
        );
    }
}
