<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;

final class ApiKeyType extends Type
{
    public const string NAME = 'api_key';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof ApiKey)) {
            throw InvalidType::new(
                value: $value,
                toType: self::NAME,
                possibleTypes: [ApiKey::class],
            );
        }

        return (string)$value;
    }

    /**
     * @throws InvalidFormat
     * @throws InvalidApiKeyException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ApiKey
    {
        if (!is_string($value)) {
            throw InvalidFormat::new(
                value: $value,
                toType: ApiKey::class,
                expectedFormat: 'string',
            );
        }

        return ApiKey::create($value);
    }
}
