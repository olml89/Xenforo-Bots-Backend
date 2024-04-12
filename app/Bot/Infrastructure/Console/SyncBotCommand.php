<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Sync\SyncBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;

class SyncBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:sync {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves a user on the remote Xenforo platform and sets is as a bot';

    /**
     * Execute the console command.
     *
     * @throws InvalidUsernameException
     * @throws BotValidationException | BotStorageException
     */
    public function handle(SyncBotUseCase $syncBot): void
    {
        $syncBotResult = $syncBot->sync(
            $name = $this->argument('name'),
            $this->argument('password'),
        );

        $this->output->success(
            sprintf('Bot <%s> synced successfully', $name)
        );
        $this->output->write(json_encode($syncBotResult, JSON_PRETTY_PRINT));
    }
}
