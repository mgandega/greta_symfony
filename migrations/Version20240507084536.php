<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507084536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C83DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_911533C83DA5256D ON conference (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C83DA5256D');
        $this->addSql('DROP INDEX UNIQ_911533C83DA5256D ON conference');
        $this->addSql('ALTER TABLE conference DROP image_id');
    }
}
