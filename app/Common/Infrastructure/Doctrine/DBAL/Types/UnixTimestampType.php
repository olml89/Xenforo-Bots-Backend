<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\InvalidUnixTimestampException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;

final class UnixTimestampType extends Type implements CustomType
{
    private const string NAME = 'unix_timestamp';

    public static function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTimeTypeDeclarationSQL($column);
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (!($value instanceof UnixTimestamp)) {
            throw InvalidType::new(
                value: $value,
                toType: self::class,
                possibleTypes: [
                    'null',
                    UnixTimestamp::class,
                ],
            );
        }

        return $value->format($platform->getDateTimeFormatString());
    }

    /**
     * @throws InvalidFormat
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UnixTimestamp
    {
        if ($value === null || $value instanceof UnixTimestamp) {
            return $value;
        }

        try {
            return UnixTimestamp::createFromFormat(
                format: $platform->getDateTimeFormatString(),
                datetime: $value,
            );
        }
        catch (InvalidUnixTimestampException $e) {
            throw InvalidFormat::new(
                value: $value,
                toType: self::class,
                expectedFormat: $platform->getDateTimeFormatString(),
                previous: $e,
            );
        }
    }
}
