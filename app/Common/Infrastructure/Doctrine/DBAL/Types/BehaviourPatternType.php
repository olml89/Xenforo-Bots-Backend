<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\TextType;
use Illuminate\Foundation\Application;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPattern;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPatternHandler;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPatternManager;
use olml89\XenforoBotsBackend\Behaviour\Domain\InvalidBehaviourPatternHandlerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BehaviourPatternType extends TextType implements CustomType, InjectableType
{
    private const string NAME = 'behaviourPattern';

    private readonly BehaviourPatternManager $behaviourPatternManager;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function inject(Application $app): void
    {
        $this->behaviourPatternManager = $app->get(BehaviourPatternManager::class);
    }

    public static function getTypeName(): string
    {
        return self::NAME;
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof BehaviourPattern)) {
            throw InvalidType::new(
                value: $value,
                toType: self::class,
                possibleTypes: [
                    BehaviourPattern::class,
                ],
            );
        }

        return $value::class;
    }

    /**
     * @throws ValueNotConvertible
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): BehaviourPattern
    {
        try {
            $behaviourPatternHandler = BehaviourPatternHandler::create($value);

            return $this->behaviourPatternManager->get($behaviourPatternHandler);
        }
        catch (InvalidBehaviourPatternHandlerException $e) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
                previous: $e,
            );
        }
    }
}
