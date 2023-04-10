<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBots\Bot\Application\CancelSubscription\CancelBotSubscriptionUseCase;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRemovalException;

final class CancelBotSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:cancel-subscription {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the bot subscription';

    /**
     * Execute the console command.
     *
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionRemovalException | BotStorageException
     */
    public function handle(CancelBotSubscriptionUseCase $cancelBotSubscription): void
    {
        $cancelBotSubscription->cancel(
            $name = $this->argument('name'),
            $this->argument('password'),
        );

        $this->output->success(
            sprintf('Bot <%s> subscription cancelled successfully', $name)
        );
    }
}
