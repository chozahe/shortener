<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703083044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link ADD COLUMN is_disposable BOOLEAN NOT NULL DEFAULT FALSE');
        $this->addSql('ALTER TABLE link ADD COLUMN expires_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__link AS SELECT id, original_url, short_id, visits, created_at, last_visited_at, is_deleted FROM link');
        $this->addSql('DROP TABLE link');
        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, original_url VARCHAR(2048) NOT NULL, short_id VARCHAR(10) NOT NULL, visits INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , last_visited_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_deleted BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO link (id, original_url, short_id, visits, created_at, last_visited_at, is_deleted) SELECT id, original_url, short_id, visits, created_at, last_visited_at, is_deleted FROM __temp__link');
        $this->addSql('DROP TABLE __temp__link');
    }
}
