<?php declare(strict_types=1);

namespace Tests\Content\Unit\Application;

use Database\Factories\ContentFactory;
use Database\Factories\SubscribedBotFactory;
use Database\Factories\ValueObjects\UuidFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\XenforoBotsBackend\Content\Application\CreatePublicInteractionUseCase;
use olml89\XenforoBotsBackend\Content\Domain\ContentRepository;
use olml89\XenforoBotsBackend\Content\Domain\ContentScope;
use olml89\XenforoBotsBackend\Content\Domain\ContentValidationException;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Common\Fakes\FakeUuidGenerator;
use Tests\Content\Fakes\InMemoryContentRepository;
use Tests\TestCase;

final class CreatePublicInteractionUseCaseTest extends TestCase
{
    private readonly ContentDataCreator $contentDataCreator;
    private readonly UuidFactory $uuidFactory;
    private readonly SubscribedBotFactory $subscribedBotFactory;
    private readonly ContentFactory $contentFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contentDataCreator = $this->resolve(ContentDataCreator::class);
        $this->uuidFactory = $this->resolve(UuidFactory::class);
        $this->subscribedBotFactory = $this->resolve(SubscribedBotFactory::class);
        $this->contentFactory = $this->resolve(ContentFactory::class);
    }

    public function tstItThrowsContentValidationExceptionIfInvalidBotIdIsProvided(): void
    {
        $invalidBotId = Str::random();

        $this->expectExceptionObject(
            ContentValidationException::fromException(new InvalidUuidException($invalidBotId))
        );

        $this
            ->resolve(CreatePublicInteractionUseCase::class)
            ->create(
                $invalidBotId,
                $this->contentDataCreator->create()
            );
    }

    public function tstItThrowsContentNotFoundExceptionIfABotWithAProvidedBotIdDoesNotExist(): void
    {
        $botId = $this->uuidFactory->create();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository()
        );

        $this->expectExceptionObject(
            BotNotFoundException::botId($botId)
        );

        $this
            ->resolve(CreatePublicInteractionUseCase::class)
            ->create(
                (string)$botId,
                $this->contentDataCreator->create()
            );
    }

    public function testItCreatesAPublicInteraction(): void
    {
        $bot = $this
            ->subscribedBotFactory
            ->create();

        $content = $this
            ->contentFactory
            ->scope(ContentScope::public)
            ->create();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($bot)
        );

        $this->app->instance(
            UuidGenerator::class,
            new FakeUuidGenerator($content->contentId())
        );

        /** @var ContentRepository $contentRepository */
        $contentRepository = $this->app->instance(
            ContentRepository::class,
            new InMemoryContentRepository()
        );

        $this
            ->resolve(CreatePublicInteractionUseCase::class)
            ->create(
                (string)$bot->botId(),
                $this
                    ->contentDataCreator
                    ->content($content)
                    ->create()
            );

        $this->assertEquals(
            $content,
            $contentRepository->getByExternalContentId(
                $content->externalContentId(), $content->scope()
            )
        );
    }
}
