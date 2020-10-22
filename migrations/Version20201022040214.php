<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022040214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedbacks DROP FOREIGN KEY FK_7E6C3F89979B1AD6');
        $this->addSql('ALTER TABLE feedbacks ADD CONSTRAINT FK_7E6C3F89979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5979B1AD6');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedbacks DROP FOREIGN KEY FK_7E6C3F89979B1AD6');
        $this->addSql('ALTER TABLE feedbacks ADD CONSTRAINT FK_7E6C3F89979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5979B1AD6');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
