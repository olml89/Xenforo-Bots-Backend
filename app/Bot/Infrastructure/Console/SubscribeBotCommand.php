<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBots\Bot\Application\Subscribe\SubscribeBotUseCase;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Subscription\Domain\SubscriptionCreationException;

final class SubscribeBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:subscribe {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribes an already existing user on the remote Xenforo platform as a bot';

    /**
     * Execute the console command.
     *
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionCreationException | BotStorageException
     */
    public function handle(SubscribeBotUseCase $subscribeBot): void
    {
        $subscribeBotResult = $subscribeBot->subscribe(
            $name = $this->argument('name'),
            $this->argument('password'),
        );

        $this->output->success(
            sprintf('Bot <%s> subscribed successfully', $name)
        );
        $this->output->write(json_encode($subscribeBotResult, JSON_PRETTY_PRINT));
    }
}
