<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Subscribe\SubscribeBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotProvisionException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionValidationException;

final class SubscribeBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:subscribe {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a local Bot tied to a new or an already existing Bot on the remote platform,
        and creates a remote BotSubscription of this backend to the remote Bot.';

    /**
     * Execute the console command.
     *
     * @throws BotAlreadyExistsException
     * @throws BotValidationException
     * @throws BotProvisionException
     * @throws SubscriptionValidationException
     * @throws SubscriptionCreationException
     * @throws BotStorageException
     */
    public function handle(SubscribeBotUseCase $subscribeBot): void
    {
        $subscribeBotResult = $subscribeBot->subscribe(
            $this->argument('username'),
            $this->argument('password'),
        );

        $this->output->success(
            sprintf('Bot \'%s\' subscribed successfully', $subscribeBotResult->username)
        );

        $this->output->write((string)$subscribeBotResult);
    }
}
