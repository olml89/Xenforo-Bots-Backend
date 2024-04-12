<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Console;

use Illuminate\Console\Command as BaseCommand;
use JsonSerializable;

abstract class Command extends BaseCommand
{
    protected function outputObject(JsonSerializable $jsonSerializable): void
    {
        $this->output->write(
            sprintf(
                '%s%s',
                json_encode($jsonSerializable, JSON_PRETTY_PRINT),
                PHP_EOL,
            )
        );
    }
}
