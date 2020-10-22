<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022042707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DA2AC2D45C');
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DABE04EA9');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summaries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DABE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DABE04EA9');
        $this->addSql('ALTER TABLE jobs_summaries DROP FOREIGN KEY FK_CD1510DA2AC2D45C');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DABE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summaries (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
