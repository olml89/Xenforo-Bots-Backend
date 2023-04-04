<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBots\Bot\Application\Create\CreateBot as CreateBotUseCase;
use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Domain\BotStorageException;

class CreateBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:create {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a bot as a user on the remote Xenforo platform';

    /**
     * Execute the console command.
     *
     * @throws BotCreationException | BotStorageException
     */
    public function handle(CreateBotUseCase $createBotUseCase): void
    {
        $createBotResult = $createBotUseCase->create(
            $this->argument('name'),
            $this->argument('password'),
        );

        $this->output->success('Bot created successfully');
        $this->output->write(json_encode($createBotResult, JSON_PRETTY_PRINT));
    }
}
