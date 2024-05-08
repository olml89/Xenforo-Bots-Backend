<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Application;

use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourAlreadyExistsException;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourCreator;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourName;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPatternHandler;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourRepository;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourStorageException;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourValidationException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final readonly class RegisterBehaviourUseCase
{
    public function __construct(
        private BehaviourCreator $behaviourCreator,
        private BehaviourRepository $behaviourRepository,
    ) {}

    /**
     * @throws BehaviourValidationException
     * @throws BehaviourAlreadyExistsException
     * @throws BehaviourStorageException
     */
    public function register(string $behaviourName, string $behaviourPatternClass): BehaviourResult
    {
        try {
            $behaviourName = BehaviourName::create($behaviourName);

            if (!is_null($alreadyExistingBehaviour = $this->behaviourRepository->getByBehaviourName($behaviourName))) {
                throw BehaviourAlreadyExistsException::behaviour($alreadyExistingBehaviour);
            }

            $behaviour = $this->behaviourCreator->create(
                behaviourName: $behaviourName,
                behaviourPatternHandler: BehaviourPatternHandler::create($behaviourPatternClass)
            );
            $this->behaviourRepository->save($behaviour);

            return new BehaviourResult($behaviour);
        }
        catch (ValueObjectException $e) {
            throw BehaviourValidationException::fromException($e);
        }
    }
}
