<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Common\Infrastructure\Database\DatabaseCreator;

final class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-database {--migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a database, this is executed after installing the application to create a testing database';

    public function handle(DatabaseCreator $databaseCreator): void
    {
        $databaseCreator->create($this);

        if ($this->option('migrate')) {
            $this->call('doctrine:migrations:migrate', [
                '--no-interaction' => true,
            ]);
        }
    }
}
