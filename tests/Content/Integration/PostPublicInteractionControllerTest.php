<?php declare(strict_types=1);

namespace Tests\Content\Integration;

use Database\Factories\BotFactory;
use Database\Factories\ValueObjects\ApiKeyFactory;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Content\Infrastructure\Http\ContentData;
use Tests\Content\Unit\Application\ContentDataCreator;
use Tests\Helpers\DoctrineTransactions;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\TestCase;

final class PostPublicInteractionControllerTest extends TestCase implements ExecutesDoctrineTransactions
{
    use DoctrineTransactions;

    private readonly BotFactory $botFactory;
    private readonly ApiKeyFactory $apiKeyFactory;
    private readonly Bot $bot;
    private readonly ContentData $contentData;
    private readonly ApiKey $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->botFactory = $this->resolve(BotFactory::class);
        $this->apiKeyFactory = $this->resolve(ApiKeyFactory::class);

        $this->bot = $this->botFactory->create();

        $this
            ->resolve(BotRepository::class)
            ->save($this->bot);

        $this->contentData = $this
            ->resolve(ContentDataCreator::class)
            ->create();

        $this->apiKey = ApiKey::create(config('app.api_key'));
    }

    private function makeRequest(?ApiKey $apiKey = null, ?string $botId = null, ?array $contentData = null): TestResponse
    {
        $botId ??= (string)$this->bot->botId();
        $contentData ??= get_object_vars($this->contentData);

        return $this->json(
            method: 'POST',
            uri: sprintf(
                '/api/bots/%s/interactions/public',
                $botId,
            ),
            data: $contentData,
            headers: is_null($apiKey) ? [] : [
                'Platform-Api-Key' => (string)$apiKey
            ],
        );
    }

    public function testItReturnsUnauthorizedResponseIfPlatformApiKeyHeaderIsMissing(): void
    {
        $this
            ->makeRequest()
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Platform-Api-Key header is not set',
            ]);
    }

    public function testItReturnsUnauthorizedResponseIfPlatformApiKeyHeaderIsInvalid(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKeyFactory->create(),
            )
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Platform-Api-Key header is not valid',
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfContentIdParameterIsMissing(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_diff_key(
                    get_object_vars($this->contentData),
                    array_flip([
                        'content_id',
                    ])
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'content_id' => [
                        'The content id field is required.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfContentIdParameterIsNotAnInteger(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'content_id' => Str::random(),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'content_id' => [
                        'The content id field must be an integer.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfContentIdParameterIsNotGreaterThanZero(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'content_id' => 0,
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'content_id' => [
                        'The content id field must be at least 1.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfParentContentIdParameterIsMissing(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_diff_key(
                    get_object_vars($this->contentData),
                    array_flip([
                        'parent_content_id',
                    ])
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'parent_content_id' => [
                        'The parent content id field is required.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfParentContentIdParameterIsNotAnInteger(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'parent_content_id' => Str::random(),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'parent_content_id' => [
                        'The parent content id field must be an integer.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfParentContentIdParameterIsNotGreaterThanZero(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'parent_content_id' => 0,
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'parent_content_id' => [
                        'The parent content id field must be at least 1.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorIdParameterIsMissing(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_diff_key(
                    get_object_vars($this->contentData),
                    array_flip([
                        'author_id',
                    ])
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_id' => [
                        'The author id field is required.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorIdParameterIsNotAnInteger(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'author_id' => Str::random(),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_id' => [
                        'The author id field must be an integer.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorIdParameterIsNotGreaterThan0(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'author_id' => 0,
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_id' => [
                        'The author id field must be at least 1.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorNameParameterIsMissing(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_diff_key(
                    get_object_vars($this->contentData),
                    array_flip([
                        'author_name',
                    ])
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_name' => [
                        'The author name field is required.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorNameParameterIsNotAString(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'author_name' => fake()->numberBetween(),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_name' => [
                        'The author name field must be a string.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorNameParameterIsShorterThan3(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'author_name' => Str::random(2),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_name' => [
                        'The author name field must be at least 3 characters.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAuthorNameParameterIsLongerThan50(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'author_name' => Str::random(51),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'author_name' => [
                        'The author name field must not be greater than 50 characters.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfCreationDateParameterIsMissing(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_diff_key(
                    get_object_vars($this->contentData),
                    array_flip([
                        'creation_date',
                    ])
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'creation_date' => [
                        'The creation date field is required.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfCreationDateParameterIsNotAValidTimestamp(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'creation_date' => Str::random(),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'creation_date' => [
                        'The creation date field must match the format U.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfEditionDateParameterIsMissing(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_diff_key(
                    get_object_vars($this->contentData),
                    array_flip([
                        'edition_date',
                    ])
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'edition_date' => [
                        'The edition date field is required.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfEditionDateParameterIsNotAValidTimestamp(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                contentData: array_merge(
                    get_object_vars($this->contentData),
                    [
                        'edition_date' => Str::random(),
                    ]
                )
            )
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'edition_date' => [
                        'The edition date field must match the format U.'
                    ]
                ],
            ]);
    }

    public function testItReturnsUnprocessableEntityResponseIfAnInvalidBotIdIsProvidedInUriParameter(): void
    {
        $invalidBotId = Str::random();

        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                botId: $invalidBotId,
            )
            ->assertUnprocessable()
            ->assertJsonPath(
                'message',
                (BotValidationException::fromException(new InvalidUuidException($invalidBotId)))->getMessage()
            );
    }

    public function testItReturnsNotFoundResponseIfANonExistentBotIdIsProvidedInUriParameter(): void
    {
        $bot = $this->botFactory->create();

        $this
            ->makeRequest(
                apiKey: $this->apiKey,
                botId: (string)$bot->botId(),
            )
            ->assertNotFound()
            ->assertJsonPath(
                'message',
                (BotNotFoundException::botId($bot->botId()))->getMessage()
            );
    }

    public function testItReturnsAcceptResponseIfAPublicInteractionIsCreated(): void
    {
        $this
            ->makeRequest(
                apiKey: $this->apiKey,
            )
            ->assertAccepted();
    }
}
