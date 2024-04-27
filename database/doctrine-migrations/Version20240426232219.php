<?php declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240426232219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE contents (
                content_id CHAR(36) NOT NULL,
                scope CHAR(7) NOT NULL,
                external_content_id INT NOT NULL,
                external_parent_content_id INT NOT NULL,
                author_author_id INT NOT NULL,
                author_author_username VARCHAR(50) NOT NULL,
                message LONGTEXT NOT NULL,
                created_at DATETIME NOT NULL,
                edited_at DATETIME NOT NULL,
                UNIQUE INDEX unique_content (scope, external_content_id),
                PRIMARY KEY(content_id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            DROP TABLE contents
        ');
    }
}
