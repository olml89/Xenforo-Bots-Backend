<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Database;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

final readonly class DatabaseCreator
{
    private const string DEFAULT_HOST = '%';

    public function __construct(
        private DatabaseManager $db,
    ) {}

    public function create(Command $command): void
    {
        $databaseCredentials = DatabaseCredentials::fromArray(
            $this->db->connection()->getConfig()
        );

        $this->createDatabase(
            database: $databaseCredentials->database,
            charset: $databaseCredentials->charset,
            collation: $databaseCredentials->collation
        );

        $command->getOutput()->success(
            sprintf(
                'Database %s created successfully or already exists',
                $databaseCredentials->database,
            )
        );

        $this->createUser(
            host: self::DEFAULT_HOST,
            database: $databaseCredentials->database,
            username: $databaseCredentials->username,
            password: $databaseCredentials->password
        );

        $command->getOutput()->success(
            sprintf(
                'User %s created successfully or already exists',
                $databaseCredentials->username,
            )
        );
    }

    private function createDatabase(string $database, string $charset, string $collation): void
    {
        $this->db->connection('root')->statement(
            sprintf(
                "CREATE DATABASE IF NOT EXISTS %s CHARACTER SET %s COLLATE %s;",
                $database,
                $charset,
                $collation,
            )
        );
    }

    private function createUser(string $host, string $database, string $username, string $password): void
    {
        $this->db->connection('root')->statement(
            sprintf(
                "CREATE USER IF NOT EXISTS '%s'@'%s' IDENTIFIED BY '%s';",
                $username,
                $host,
                $password,
            )
        );
        $this->db->connection('root')->statement(
            sprintf(
                "GRANT ALL PRIVILEGES ON %s.* TO '%s'@'%s';",
                $database,
                $username,
                $host,
            )
        );
        $this->db->connection('root')->statement(
            "FLUSH PRIVILEGES;"
        );
    }
}
