<?php declare(strict_types=1);

namespace Tests\Content\Fakes;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Content\Domain\AutoId;
use olml89\XenforoBotsBackend\Content\Domain\Content;
use olml89\XenforoBotsBackend\Content\Domain\ContentRepository;
use olml89\XenforoBotsBackend\Content\Domain\ContentScope;
use WeakMap;

final class InMemoryContentRepository implements ContentRepository
{
    /**
     * @var WeakMap<Uuid, Content>
     */
    private WeakMap $contents;

    public function __construct(Content ...$contents)
    {
        $this->contents = new WeakMap();

        foreach ($contents as $content) {
            $this->contents[$content->contentId()] = $content;
        }
    }

    public function getByExternalContentId(AutoId $externalContentId, ContentScope $scope): ?Content
    {
        foreach ($this->contents as $content) {
            if ($content->externalContentId()->equals($externalContentId) && $content->hasScope($scope)) {
                return $content;
            }
        }

        return null;
    }

    public function save(Content $content): void
    {
        $this->contents[$content->contentId()] = $content;
    }
}
