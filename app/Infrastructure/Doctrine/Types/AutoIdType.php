<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBots\Domain\ValueObjects\AutoId\AutoId;

final class AutoIdType extends Type
{
    private const NAME = 'AutoId';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'integer';
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): int
    {
        if (!($value instanceof AutoId)) {
            throw ConversionException::conversionFailed($value, AutoId::class);
        }

        return $value->toInt();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): AutoId
    {
        if (!is_int($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return new AutoId($value);
    }
}
