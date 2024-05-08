<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\StringType;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourName;
use olml89\XenforoBotsBackend\Behaviour\Domain\InvalidBehaviourNameException;

final class BehaviourNameType extends StringType implements CustomType
{
    private const string NAME = 'behaviourName';

    public static function getTypeName(): string
    {
        return self::NAME;
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof BehaviourName)) {
            throw InvalidType::new(
                value: $value,
                toType: self::class,
                possibleTypes: [
                    BehaviourName::class,
                ],
            );
        }

        return (string)$value;
    }

    /**
     * @throws ValueNotConvertible
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): BehaviourName
    {
        try {
            return BehaviourName::create($value);
        }
        catch (InvalidBehaviourNameException $e) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
                previous: $e,
            );
        }
    }
}
