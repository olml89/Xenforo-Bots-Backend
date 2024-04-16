<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;

abstract class TestCase extends BaseTestCase
{
    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    public function resolve(string $className): object
    {
        return $this->app[$className];
    }

    protected function setUp(): void
    {
        parent::setUp();

        if ($this instanceof ExecutesDoctrineTransactions) {
            $this->beginDoctrineTransaction();
        }

        if ($this instanceof InteractsWithXenforoApi) {
            $this->setUpXenforoApiConsumer();
        }
    }
}
