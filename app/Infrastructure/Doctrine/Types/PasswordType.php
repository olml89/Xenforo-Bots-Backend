<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBots\Domain\ValueObjects\Password\Password;
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
        return 'string';
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Password)) {
            throw ConversionException::conversionFailed($value, Password::class);
        }

        try {
            $reflectionClass = new ReflectionClass(Password::class);
            $reflectionHashProperty = $reflectionClass->getProperty('hash');
            $reflectionHashProperty->setAccessible(true);

            return $reflectionHashProperty->getValue($value);
        }
        catch (ReflectionException) {
            throw ConversionException::conversionFailed($value, Password::class);
        }
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Password
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        try {
            $reflectionClass = new ReflectionClass(Password::class);
            $password = $reflectionClass->newInstanceWithoutConstructor();
            $reflectionHashProperty = $reflectionClass->getProperty('hash');
            $reflectionHashProperty->setAccessible(true);
            $reflectionHashProperty->setValue($password, $value);

            return $password;
        }
        catch (ReflectionException) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }
}
