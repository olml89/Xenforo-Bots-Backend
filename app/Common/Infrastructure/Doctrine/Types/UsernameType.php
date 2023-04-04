<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use ReflectionClass;
use ReflectionException;

final class UsernameType extends Type
{
    private const NAME = 'username';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Username)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), Username::class);
        }

        return (string)$value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Username
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailedFormat($value, Username::class, $this->getName());
        }

        return new Username($value);
    }
}
