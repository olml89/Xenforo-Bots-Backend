<?php declare(strict_types=1);

namespace Tests\Content\Fakes;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Content\Domain\Content;
use olml89\XenforoBotsBackend\Content\Domain\ContentRepository;
use olml89\XenforoBotsBackend\Content\Domain\ContentSpecification;
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

    public function getOneBy(ContentSpecification $specification): ?Content
    {
        foreach ($this->contents as $content) {
            if ($specification->isSatisfiedBy($content)) {
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
