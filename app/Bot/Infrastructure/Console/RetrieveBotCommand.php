<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Retrieve\RetrieveBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;

final class RetrieveBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:retrieve {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves a local Bot by username.';

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     */
    public function handle(RetrieveBotUseCase $retrieveBot): void
    {
        $botResult = $retrieveBot->retrieve(
            $this->argument('username')
        );

        $this->output->success(
            sprintf('Bot \'%s\' retrieved successfully', $botResult->username)
        );

        $this->output->write((string)$botResult);
    }
}
