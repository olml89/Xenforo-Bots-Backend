<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;

final class UsernameType extends Type
{
    public const string NAME = 'username';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Username)) {
            throw InvalidType::new(
                value: $value,
                toType: self::NAME,
                possibleTypes: [Username::class],
            );
        }

        return (string)$value;
    }

    /**
     * @throws InvalidFormat
     * @throws InvalidUsernameException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Username
    {
        if (!is_string($value)) {
            throw InvalidFormat::new(
                value: $value,
                toType: Username::class,
                expectedFormat: 'string',
            );
        }

        return Username::create($value);
    }
}