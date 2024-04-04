<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Faker\Generator as Faker;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

final class UuidFactory
{
    public function __construct(
        private readonly Faker $faker,
    ) {}

    /**
     * @throws ReflectionException
     */
    public function create(string $value): Uuid
    {
        $reflectionClass = new ReflectionClass(Uuid::class);
        $uuid = $reflectionClass->newInstanceWithoutConstructor();
        $property = $reflectionClass->getParentClass()->getProperty('value');
        $property->setAccessible(true);
        $property->setValue($uuid, $value);

        return $uuid;
    }

    /**
     * @throws ReflectionException
     */
    public function random(): Uuid
    {
        return $this->create(
            $this->faker->uuid(),
        );
    }
}
