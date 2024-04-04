<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use ReflectionClass;
use ReflectionException;

final class UrlType extends Type
{
    private const NAME = 'url';

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
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (!($value instanceof Url)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        }

        return (string)$value;
    }

    /**
     * @throws ConversionException
     * @throws ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Url
    {
        if (is_null($value)) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedFormat($value, Url::class, $this->getName());
        }

        $url = (new ReflectionClass(Url::class))->newInstanceWithoutConstructor();
        $property = (new ReflectionClass($url))->getParentClass()->getProperty('value');
        $property->setAccessible(true);
        $property->setValue($url, $value);

        return $url;
    }
}
