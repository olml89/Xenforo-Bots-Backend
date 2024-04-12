<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Illuminate\Foundation\Application;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\InvalidUrlException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class UrlType extends InjectableType
{
    public const string NAME = 'url';

    private readonly UrlValidator $urlValidator;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function inject(Application $app): void
    {
        $this->urlValidator = $app->get(UrlValidator::class);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Url)) {
            throw InvalidType::new(
                value: $value,
                toType: self::NAME,
                possibleTypes: [Url::class],
            );
        }

        return (string)$value;
    }

    /**
     * @throws InvalidFormat
     * @throws InvalidUrlException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Url
    {
        if (!is_string($value)) {
            throw InvalidFormat::new(
                value: $value,
                toType: Url::class,
                expectedFormat: 'string',
            );
        }

        return Url::create($value, $this->urlValidator);
    }
}
