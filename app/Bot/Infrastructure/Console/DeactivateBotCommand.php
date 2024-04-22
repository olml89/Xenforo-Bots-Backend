<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Deactivate\DeactivateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;

final class DeactivateBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:deactivate {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates a Bot by making its BotSubscription on the remote Xenforo platform inactive.';

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     * @throws BotDeactivationException
     * @throws BotStorageException
     */
    public function handle(DeactivateBotUseCase $deactivateBot): void
    {
        $deactivateBot->deactivate(
            $username = $this->argument('username'),
        );

        $this->output->success(
            sprintf('Bot \'%s\' deactivated successfully', $username)
        );
    }
}
