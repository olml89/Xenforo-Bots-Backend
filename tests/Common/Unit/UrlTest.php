<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\InvalidUrlException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;
use Tests\TestCase;

final class UrlTest extends TestCase
{
    private readonly UrlValidator $urlValidator;

    public function setUp(): void
    {
        parent::setUp();

        $this->urlValidator = $this->resolve(UrlValidator::class);
    }

    public function testItDoesNotAllowInvalidUrls(): void
    {
        $value = Str::random();

        $this->expectExceptionObject(
            new InvalidUrlException($value)
        );

        Url::create($value, $this->urlValidator);
    }

    public function testItCreatesUrl(): void
    {
        $value = fake()->url();

        $url = Url::create($value, $this->urlValidator);

        $this->assertEquals(
            $value,
            $url->value()
        );
        $this->assertEquals(
            $value,
            (string)$url
        );
    }
}
