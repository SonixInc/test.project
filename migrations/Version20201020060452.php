<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201020060452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jobs_summaries (job_id INT NOT NULL, summary_id INT NOT NULL, INDEX IDX_CD1510DABE04EA9 (job_id), INDEX IDX_CD1510DA2AC2D45C (summary_id), PRIMARY KEY(job_id, summary_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DABE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_summaries ADD CONSTRAINT FK_CD1510DA2AC2D45C FOREIGN KEY (summary_id) REFERENCES summaries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE summaries ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE summaries ADD CONSTRAINT FK_66783CCAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_66783CCAA76ED395 ON summaries (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jobs_summaries');
        $this->addSql('ALTER TABLE summaries DROP FOREIGN KEY FK_66783CCAA76ED395');
        $this->addSql('DROP INDEX IDX_66783CCAA76ED395 ON summaries');
        $this->addSql('ALTER TABLE summaries DROP user_id');
    }
}
