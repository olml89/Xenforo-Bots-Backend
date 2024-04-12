<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

class UuidType extends Type
{
    public const string NAME = 'uuid';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Uuid)) {
            throw InvalidType::new(
                value: $value,
                toType: self::NAME,
                possibleTypes: [Uuid::class],
            );
        }

        return (string)$value;
    }

    /**
     * @throws InvalidFormat
     * @throws InvalidUuidException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Uuid
    {
        if (!is_string($value)) {
            throw InvalidFormat::new(
                value: $value,
                toType: Uuid::class,
                expectedFormat: 'string',
            );
        }

        return Uuid::create($value);
    }
}
