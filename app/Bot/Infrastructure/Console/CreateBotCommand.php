<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Bot\Application\Create\CreateBotUseCase as CreateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;

final class CreateBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:create {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a bot on the remote Xenforo platform';

    /**
     * Execute the console command.
     *
     * @throws BotValidationException
     * @throws BotAlreadyExistsException
     * @throws BotCreationException
     * @throws BotStorageException
     */
    public function handle(CreateBotUseCase $createBot): void
    {
        $createBotResult = $createBot->create(
            $this->argument('username'),
            $this->argument('password'),
        );

        $this->output->success(
            sprintf('Bot \'%s\' created successfully', $createBotResult->username)
        );

        $this->output->write((string)$createBotResult);
    }
}
