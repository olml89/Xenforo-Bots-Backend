<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\ShowSubscription\ShowBotSubscriptionUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;

final class ShowBotSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:show-subscription {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows the current local status of the Bot subscription';

    /**
     * Execute the console command.
     *
     * @throws InvalidUsernameException
     * @throws BotNotFoundException
     */
    public function handle(ShowBotSubscriptionUseCase $showBotSubscription): void
    {
        $showBotSubscriptionResult = $showBotSubscription->retrieve(
            $name = $this->argument('name'),
            $this->argument('password'),
        );

        $outputMessage = is_null($showBotSubscriptionResult)
            ? sprintf('Bot <%s> is currently not subscribed (locally)', $name)
            : sprintf(
                'Bot <%s> is currently subscribed locally',
                $name,
            );

        is_null($showBotSubscriptionResult)
            ? $this->output->error($outputMessage)
            : $this->output->success($outputMessage);

        if (!is_null($showBotSubscriptionResult)) {
            $this->output->write(json_encode($showBotSubscriptionResult, JSON_PRETTY_PRINT));
        }
    }
}
