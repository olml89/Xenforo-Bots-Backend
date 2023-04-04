<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

final class UuidType extends Type
{
    private const NAME = 'uuid';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Uuid)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), Uuid::class);
        }

        return (string)$value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Uuid
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailedFormat($value, Uuid::class, $this->getName());
        }

        try {
            $reflectionClass = new ReflectionClass(Uuid::class);
            $uuid = $reflectionClass->newInstanceWithoutConstructor();
            $reflectionClass->getParentClass()->getProperty('value')->setAccessible(true);
            $reflectionClass->getParentClass()->getProperty('value')->setValue($uuid, $value);

            return $uuid;
        }
        catch (ReflectionException) {
            throw ConversionException::conversionFailedFormat($value, Uuid::class, $this->getName());
        }
    }
}
