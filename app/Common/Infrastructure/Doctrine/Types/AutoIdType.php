<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\AutoId\AutoId;

final class AutoIdType extends Type
{
    private const NAME = 'auto_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): int
    {
        if (!($value instanceof AutoId)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['int']);
        }

        return $value->toInt();
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): AutoId
    {
        if (!is_int($value)) {
            throw ConversionException::conversionFailedFormat($value, AutoId::class, $this->getName());
        }

        return new AutoId($value);
    }
}
