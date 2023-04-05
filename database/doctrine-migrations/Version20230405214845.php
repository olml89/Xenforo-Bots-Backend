<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405214845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bots (id CHAR(36) NOT NULL, subscription_id CHAR(36) DEFAULT NULL, user_id INT UNSIGNED NOT NULL, name VARCHAR(50) NOT NULL, password VARCHAR(72) NOT NULL, registered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_71BFF0FDA76ED395 (user_id), UNIQUE INDEX UNIQ_71BFF0FD5E237E06 (name), UNIQUE INDEX UNIQ_71BFF0FD9A1887DC (subscription_id), INDEX idx_id (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriptions (id CHAR(36) NOT NULL, bot_id CHAR(36) DEFAULT NULL, xenforo_url VARCHAR(255) NOT NULL, subscribed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_4778A0192C1C487 (bot_id), INDEX idx_id (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bots ADD CONSTRAINT FK_71BFF0FD9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscriptions (id)');
        $this->addSql('ALTER TABLE subscriptions ADD CONSTRAINT FK_4778A0192C1C487 FOREIGN KEY (bot_id) REFERENCES bots (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bots DROP FOREIGN KEY FK_71BFF0FD9A1887DC');
        $this->addSql('ALTER TABLE subscriptions DROP FOREIGN KEY FK_4778A0192C1C487');
        $this->addSql('DROP TABLE bots');
        $this->addSql('DROP TABLE subscriptions');
    }
}
