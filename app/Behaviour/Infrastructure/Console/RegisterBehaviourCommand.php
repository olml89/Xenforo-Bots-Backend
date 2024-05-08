<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\XenforoBotsBackend\Behaviour\Application\RegisterBehaviourUseCase;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourAlreadyExistsException;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourStorageException;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourValidationException;

final class RegisterBehaviourCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'behaviour:register {behaviourName} {behaviourPatternHandler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers a Behaviour associating a behaviour name to a behaviour pattern handler.';

    /**
     * Execute the console command.
     *
     * @throws BehaviourValidationException
     * @throws BehaviourAlreadyExistsException
     * @throws BehaviourStorageException
     */
    public function handle(RegisterBehaviourUseCase $registerBehaviour): void
    {
        $registerBehaviourResult = $registerBehaviour->register(
            $this->argument('behaviourName'),
            $this->argument('behaviourPatternHandler'),
        );

        $this->output->success(
            sprintf('Behaviour \'%s\' registered successfully', $registerBehaviourResult->name)
        );

        $this->output->write((string)$registerBehaviourResult);
    }
}
