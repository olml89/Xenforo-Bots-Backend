<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Subscribe;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotProvider;
use olml89\XenforoBotsBackend\Bot\Domain\BotProvisionException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotSubscriber;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\EqualsUsernameSpecification;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final readonly class SubscribeBotUseCase
{
    public function __construct(
        private BotRepository $botRepository,
        private BotProvider $botProvider,
        private BotSubscriber $botSubscriber,
    ) {}

    /**
     * @throws BotAlreadyExistsException
     * @throws BotValidationException
     * @throws BotProvisionException
     * @throws SubscriptionValidationException
     * @throws SubscriptionCreationException
     * @throws BotStorageException
     */
    public function subscribe(string $username, string $password): BotResult
    {
        try {
            $username = Username::create($username);

            $alreadyExistingBot = $this
                ->botRepository
                ->getOneBy(new EqualsUsernameSpecification($username));

            if (!is_null($alreadyExistingBot)) {
                throw BotAlreadyExistsException::bot($alreadyExistingBot);
            }

            $bot = $this
                ->botProvider
                ->provide($username, Password::create($password));

            $this
                ->botSubscriber
                ->subscribe($bot);

            return new BotResult($bot);
        }
        catch (ValueObjectException $e) {
            throw BotValidationException::fromException($e);
        }
    }
}
