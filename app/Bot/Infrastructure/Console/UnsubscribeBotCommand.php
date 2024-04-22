<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Unsubscribe\UnsubscribeBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionRemovalException;

final class UnsubscribeBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:unsubscribe {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the BotSubscription of this backend on the remote Xenforo platform and
        deletes the local Bot.';

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     * @throws SubscriptionRemovalException
     * @throws BotStorageException
     */
    public function handle(UnsubscribeBotUseCase $unsubscribeBot): void
    {
        $unsubscribeBot->unsubscribe(
            $username = $this->argument('username')
        );

        $this->output->success(
            sprintf('Bot \'%s\' unsubscribed successfully', $username)
        );
    }
}
