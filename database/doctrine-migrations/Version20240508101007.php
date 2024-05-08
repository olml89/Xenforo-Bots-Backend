<?php declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240508101007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE behaviours (
                behaviour_id CHAR(36) NOT NULL,
                name VARCHAR(50) NOT NULL,
                pattern VARCHAR(100) NOT NULL,
                UNIQUE INDEX UNIQ_A28009735E237E06 (name),
                UNIQUE INDEX UNIQ_A2800973A3BCFC8E (pattern),
                PRIMARY KEY(behaviour_id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            DROP TABLE behaviours
        ');
    }
}
