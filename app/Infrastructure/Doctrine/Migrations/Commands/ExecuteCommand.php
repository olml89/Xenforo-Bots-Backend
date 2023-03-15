<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Doctrine\Migrations\Commands;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand as DoctrineExecuteCommand;
use Symfony\Component\Console\Exception\ExceptionInterface;

class ExecuteCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'doctrine:migrations:execute {versions* : The versions to execute }
        {--em= : For a specific EntityManager. }
        {--write-sql= : The path to output the migration SQL file instead of executing it. }
        {--dry-run : Execute the migration as a dry run. }
        {--up : Execute the migration up. }
        {--down : Execute the migration down. }
        {--query-time : Time all the queries individually.}
    ';

    /**
     * @var string
     */
    protected $description = 'Execute a single migration version up or down manually.';

    public function __construct()
    {
        parent::__construct();

        $this->getDefinition()->getOption('write-sql')->setDefault(false);
    }

    /**
     * Execute the console command.
     */
    public function handle(DependencyFactory $dependencyFactory): int
    {
        $command = new DoctrineExecuteCommand($dependencyFactory);

        try {
            return $command->run(
                input: $this->getDoctrineInput($command),
                output: $this->output->getOutput(),
            );
        } catch (ExceptionInterface $e) {
            $this->error($e->getMessage());
            return 0;
        }
    }
}
