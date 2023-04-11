<?php declare(strict_types=1);

namespace olml89\XenforoBots\Reply\Application\Create\Post;

final class PostData
{
    public function __construct(
        public readonly int $post_id,
        public readonly int $thread_id,
        public readonly int $author_id,
        public readonly string $author_name,
        public readonly int $create_date,
        public readonly int $update_date,
        public readonly string $message,
    ) {}
}
