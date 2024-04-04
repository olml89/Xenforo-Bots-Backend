<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\UuidFactory;
use Faker\Generator as Faker;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use ReflectionException;

final class BotFactory
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        private readonly Hasher $hasher,
        private readonly Faker $faker,
    ) {}

    /**
     * @throws ReflectionException
     */
    public function create(
        string $id = null,
        int $user_id = null,
        string $name = null,
        string $password = null,
        int $registered_at = null,
    ): Bot
    {
        return new Bot(
            id: $this->uuidFactory->create($id ?? $this->faker->uuid()),
            userId: new AutoId($user_id ?? $this->faker->numberBetween(1)),
            name: new Username($name ?? $this->faker->userName()),
            password: new Password($password ?? $this->faker->password(), $this->hasher),
            registeredAt: UnixTimestamp::toDateTimeImmutable($registered_at ?? time()),
        );
    }
}
