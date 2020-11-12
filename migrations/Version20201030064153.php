<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201030064153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_chats (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CFAAE357F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_chat_users (chat_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B2998E9E1A9A7125 (chat_id), INDEX IDX_B2998E9EA76ED395 (user_id), PRIMARY KEY(chat_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_chats ADD CONSTRAINT FK_CFAAE357F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_chat_users ADD CONSTRAINT FK_B2998E9E1A9A7125 FOREIGN KEY (chat_id) REFERENCES user_chats (id)');
        $this->addSql('ALTER TABLE user_chat_users ADD CONSTRAINT FK_B2998E9EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD chat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E961A9A7125 FOREIGN KEY (chat_id) REFERENCES user_chats (id)');
        $this->addSql('CREATE INDEX IDX_DB021E961A9A7125 ON messages (chat_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E961A9A7125');
        $this->addSql('ALTER TABLE user_chat_users DROP FOREIGN KEY FK_B2998E9E1A9A7125');
        $this->addSql('DROP TABLE user_chats');
        $this->addSql('DROP TABLE user_chat_users');
        $this->addSql('DROP INDEX IDX_DB021E961A9A7125 ON messages');
        $this->addSql('ALTER TABLE messages DROP chat_id');
    }
}
