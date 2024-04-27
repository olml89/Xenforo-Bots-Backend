<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Infrastructure\Http;

final readonly class ContentData
{
    public function __construct(
        public int $content_id,
        public int $parent_content_id,
        public int $author_id,
        public string $author_name,
        public int $creation_date,
        public int $edition_date,
        public string $message,
    ) {}
}
