<?php declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240417121424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE bots (
                bot_id CHAR(36) NOT NULL,
                api_key VARCHAR(32) NOT NULL,
                username VARCHAR(50) NOT NULL,
                subscription_id CHAR(36) DEFAULT NULL,
                UNIQUE INDEX UNIQ_71BFF0FDC912ED9D (api_key),
                UNIQUE INDEX UNIQ_71BFF0FDF85E0677 (username),
                UNIQUE INDEX UNIQ_71BFF0FD9A1887DC (subscription_id),
                PRIMARY KEY(bot_id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
        $this->addSql('
            CREATE TABLE subscriptions (
                subscription_id CHAR(36) NOT NULL,
                is_active TINYINT(1) NOT NULL,
                subscribed_at DATETIME NOT NULL,
                activation_changed_at DATETIME NOT NULL,
                PRIMARY KEY(subscription_id)
            ) DEFAULT CHARACTER SET utf8mb4'
        );
        $this->addSql('
            ALTER TABLE bots
            ADD CONSTRAINT FK_71BFF0FD9A1887DC
            FOREIGN KEY (subscription_id)
            REFERENCES subscriptions (subscription_id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE bots
            DROP FOREIGN KEY FK_71BFF0FD9A1887DC
        ');
        $this->addSql('
            ALTER TABLE subscriptions
            DROP FOREIGN KEY FK_4778A0192C1C487
        ');
        $this->addSql('
            DROP TABLE bots
        ');
        $this->addSql('
            DROP TABLE subscriptions
        ');
    }
}
