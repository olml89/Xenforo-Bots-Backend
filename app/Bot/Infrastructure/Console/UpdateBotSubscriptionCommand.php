<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\UpdateSubscription\UpdateBotSubscriptionUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRetrievalException;

final class UpdateBotSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:update-subscription {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates and shows the local bot subscription status depending on the subscription
        status on the remote Xenforo platform';

    /**
     * Execute the console command.
     *
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionRetrievalException | BotStorageException
     */
    public function handle(UpdateBotSubscriptionUseCase $updateBotSubscription): void
    {
        $updateBotSubscriptionResult = $updateBotSubscription->update(
            $name = $this->argument('name'),
            $this->argument('password'),
        );

        $outputMessage = is_null($updateBotSubscriptionResult)
            ? sprintf('Bot <%s> is currently not subscribed', $name)
            : sprintf(
                'Bot <%s> is currently subscribed in \'%s\'',
                $name,
                $updateBotSubscriptionResult->subscription->xenforo_url,
            );

        is_null($updateBotSubscriptionResult)
            ? $this->output->error($outputMessage)
            : $this->output->success($outputMessage);

        if (!is_null($updateBotSubscriptionResult)) {
            $this->output->write(json_encode($updateBotSubscriptionResult, JSON_PRETTY_PRINT));
        }
    }
}
