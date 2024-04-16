<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415134015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE bots (
                api_key VARCHAR(32) NOT NULL,
                username VARCHAR(50) NOT NULL,
                registered_at DATETIME NOT NULL,
                bot_id CHAR(36) NOT NULL,
                UNIQUE INDEX UNIQ_71BFF0FDC912ED9D (api_key),
                UNIQUE INDEX UNIQ_71BFF0FDF85E0677 (username),
                PRIMARY KEY(bot_id)
            ) DEFAULT CHARACTER SET utf8mb4'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bots');
    }
}
