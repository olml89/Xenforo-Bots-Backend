<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Index\IndexBotsUseCase;

final class IndexBotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all the local Bots in this backend.';

    public function handle(IndexBotsUseCase $indexBots): void
    {
        $botResults = $indexBots->index();

        if (count($botResults) === 0) {
            $this->output->write(sprintf(
                'There are no Bots in this backend%s',
                PHP_EOL,
            ));

            return;
        }

        $this->output->success('Bots indexed successfully');

        foreach ($botResults as $botResult) {
            $this->output->write((string)$botResult);
        }
    }
}
