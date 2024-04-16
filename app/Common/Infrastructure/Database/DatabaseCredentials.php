<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Database;

final readonly class DatabaseCredentials
{
    private function __construct(
        public string $database,
        public string $charset,
        public string $collation,
        public string $username,
        public string $password,
    ) {}

    public static function fromArray(array $array): self
    {
        return new self(
            database: $array['database'],
            charset: $array['charset'],
            collation: $array['collation'],
            username: $array['username'],
            password: $array['password']
        );
    }
}
