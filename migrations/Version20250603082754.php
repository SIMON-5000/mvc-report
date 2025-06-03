<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603082754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE album (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cover_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, position INTEGER NOT NULL, visible BOOLEAN NOT NULL, CONSTRAINT FK_39986E43922726E9 FOREIGN KEY (cover_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_39986E43922726E9 ON album (cover_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, album_id INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, album VARCHAR(255) NOT NULL, position INTEGER NOT NULL, path VARCHAR(255) NOT NULL, visible BOOLEAN NOT NULL, CONSTRAINT FK_C53D045F1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045F1137ABCF ON image (album_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE img2_cat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, img_id INTEGER NOT NULL, category_id INTEGER NOT NULL, CONSTRAINT FK_7C861896C06A9F55 FOREIGN KEY (img_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7C86189612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C861896C06A9F55 ON img2_cat (img_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C86189612469DE2 ON img2_cat (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE album
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE img2_cat
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
