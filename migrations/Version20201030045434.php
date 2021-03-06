<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201030045434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DB021E96A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_messages (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, user_id INT NOT NULL, viewed TINYINT(1) NOT NULL, INDEX IDX_3B8FFA96537A1329 (message_id), INDEX IDX_3B8FFA96A76ED395 (user_id), UNIQUE INDEX UNIQ_3B8FFA96537A1329A76ED395 (message_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_subscribtions (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, customer_id VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, canceled TINYINT(1) NOT NULL, current_period_start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', current_period_end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_A4E784C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_messages ADD CONSTRAINT FK_3B8FFA96537A1329 FOREIGN KEY (message_id) REFERENCES messages (id)');
        $this->addSql('ALTER TABLE user_messages ADD CONSTRAINT FK_3B8FFA96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_subscribtions ADD CONSTRAINT FK_A4E784C1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_messages DROP FOREIGN KEY FK_3B8FFA96537A1329');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE user_messages');
        $this->addSql('DROP TABLE user_subscribtions');
    }
}
