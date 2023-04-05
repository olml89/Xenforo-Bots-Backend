<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use ReflectionClass;
use ReflectionException;

final class PasswordType extends Type
{
    private const NAME = 'password';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Password)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        }

        $reflectionClass = new ReflectionClass(Password::class);
        $reflectionHashProperty = $reflectionClass->getProperty('hash');
        $reflectionHashProperty->setAccessible(true);

        return $reflectionHashProperty->getValue($value);
    }

    /**
     * @throws ConversionException
     * @throws ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Password
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailedFormat($value, Password::class, $this->getName());
        }

        $reflectionClass = new ReflectionClass(Password::class);
        $password = $reflectionClass->newInstanceWithoutConstructor();
        $reflectionHashProperty = $reflectionClass->getProperty('hash');
        $reflectionHashProperty->setAccessible(true);
        $reflectionHashProperty->setValue($password, $value);

        return $password;
    }
}
