<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240527083005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_911533C8A76ED395 ON conference (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649604B8382');
        $this->addSql('DROP INDEX IDX_8D93D649604B8382 ON user');
        $this->addSql('ALTER TABLE user DROP conference_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C8A76ED395');
        $this->addSql('DROP INDEX IDX_911533C8A76ED395 ON conference');
        $this->addSql('ALTER TABLE conference DROP user_id');
        $this->addSql('ALTER TABLE user ADD conference_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649604B8382 ON user (conference_id)');
    }
}
