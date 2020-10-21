<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201021095245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DA2AC2D45C');
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DABE04EA9');
        $this->addSql('ALTER TABLE jobs_summaries ADD id INT AUTO_INCREMENT NOT NULL, ADD viewed TINYINT(1) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summaries (id)');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DABE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD1510DABE04EA92AC2D45C ON jobs_summaries (job_id, summary_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs DROP name');
        $this->addSql('ALTER TABLE jobs_summaries MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DABE04EA9');
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DA2AC2D45C');
        $this->addSql('DROP INDEX UNIQ_CD1510DABE04EA92AC2D45C ON jobs_summaries');
        $this->addSql('ALTER TABLE jobs_summaries DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jobs_summaries DROP id, DROP viewed');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DABE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summaries (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_summaries ADD PRIMARY KEY (job_id, summary_id)');
    }
}
