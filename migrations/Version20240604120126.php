<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604120126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conference_competence (conference_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_6A4CDF58604B8382 (conference_id), INDEX IDX_6A4CDF5815761DAB (competence_id), PRIMARY KEY(conference_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conference_competence ADD CONSTRAINT FK_6A4CDF58604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conference_competence ADD CONSTRAINT FK_6A4CDF5815761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference_competence DROP FOREIGN KEY FK_6A4CDF58604B8382');
        $this->addSql('ALTER TABLE conference_competence DROP FOREIGN KEY FK_6A4CDF5815761DAB');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE conference_competence');
    }
}
