<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Content\Domain\AutoId;
use olml89\XenforoBotsBackend\Content\Domain\InvalidAutoIdException;

final class AutoIdType extends Type implements CustomType
{
    private const string NAME = 'auto_id';

    public static function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): int
    {
        if (!($value instanceof AutoId)) {
            throw InvalidType::new(
                value: $value,
                toType: self::class,
                possibleTypes: [
                    AutoId::class,
                ],
            );
        }

        return $value->value();
    }

    /**
     * @throws ValueNotConvertible
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): AutoId
    {
        try {
            return AutoId::create($value);
        }
        catch (InvalidAutoIdException $e) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
                previous: $e,
            );
        }
    }
}
