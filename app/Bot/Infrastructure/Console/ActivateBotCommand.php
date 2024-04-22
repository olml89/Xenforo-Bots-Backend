<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Activate\ActivateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotActivationException;

final class ActivateBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:activate {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activates a Bot by making its BotSubscription on the remote Xenforo platform active.';

    /**
     * Execute the console command.
     *
     * @throws BotValidationException
     * @throws BotActivationException
     * @throws BotNotFoundException
     * @throws BotStorageException
     */
    public function handle(ActivateBotUseCase $activateBot): void
    {
        $activateBot->activate(
            $username = $this->argument('username'),
        );

        $this->output->success(
            sprintf('Bot \'%s\' activated successfully', $username)
        );
    }
}
