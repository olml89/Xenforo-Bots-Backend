<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Laravel\Console\Commands;

use olml89\XenforoBots\Application\UseCases\Bot\Create\CreateBot as CreateBotUseCase;
use Illuminate\Console\Command;

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
