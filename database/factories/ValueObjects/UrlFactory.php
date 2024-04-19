<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Faker\Generator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;

final readonly class UrlFactory
{
    public function __construct(
        private Generator $faker,
        private UrlValidator $urlValidator,
    ) {}

    public function create(): Url
    {
        return Url::create($this->faker->url(), $this->urlValidator);
    }
}
