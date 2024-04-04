<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

class UuidType extends Type
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

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (!($value instanceof Uuid)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        }

        return (string)$value;
    }

    /**
     * @throws ConversionException
     * @throws ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Uuid
    {
        if (is_null($value)) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedFormat($value, Uuid::class, $this->getName());
        }

        $reflectionClass = new ReflectionClass(Uuid::class);
        $uuid = $reflectionClass->newInstanceWithoutConstructor();
        $reflectionClass->getParentClass()->getProperty('value')->setAccessible(true);
        $reflectionClass->getParentClass()->getProperty('value')->setValue($uuid, $value);

        return $uuid;
    }
}
