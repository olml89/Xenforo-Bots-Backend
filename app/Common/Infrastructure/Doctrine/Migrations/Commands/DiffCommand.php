<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Doctrine\Migrations\Commands;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Generator\Exception\NoChangesDetected;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand as DoctrineDiffCommand;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Exception\ExceptionInterface;

final class DiffCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctrine:migrations:diff
        {--em= : For a specific EntityManager. }
        {--filter-expression= : Tables which are filtered by Regular Expression.}
        {--formatted : Format the generated SQL. }
        {--line-length=120 : Max line length of unformatted lines.}
        {--check-database-platform= : Check Database Platform to the generated code.}
        {--allow-empty-diff : Do not throw an exception when no changes are detected. }
        {--from-empty-schema : Generate a full migration as if the current database was empty. }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a migration by comparing your current database to your mapping information.';

    public function __construct()
    {
        parent::__construct();

        $this->getDefinition()->getOption('check-database-platform')->setDefault(false);
    }

    /**
     * Execute the console command.
     */
    public function handle(
        DependencyFactory $dependencyFactory,
        Repository $config,
    ): int
    {
        $command = new DoctrineDiffCommand($dependencyFactory);

        if ($this->input->getOption('filter-expression') === null) {
            $this->input->setOption(
                name: 'filter-expression',
                value: $config->get('doctrine.migrations.schema.filter'),
            );
        }

        try {
            return $command->run(
                input: $this->getDoctrineInput($command),
                output: $this->output->getOutput(),
            );
        }
        catch (NoChangesDetected|ExceptionInterface $e) {
            $this->error($e->getMessage());
            return 0;
        }
    }
}
