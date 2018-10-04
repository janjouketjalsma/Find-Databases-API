<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181004061111 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_64C19C1796A8F92 ON category (parent_category_id)');
        $this->addSql('CREATE TABLE "database" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, title_remainder VARCHAR(255) DEFAULT NULL, part_title VARCHAR(255) DEFAULT NULL, title_org_script VARCHAR(255) DEFAULT NULL, sorting_title VARCHAR(255) DEFAULT NULL, publisher VARCHAR(255) DEFAULT NULL, publisher_website VARCHAR(255) DEFAULT NULL, publisher_website_link_text VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, last_modified DATE NOT NULL, bibid VARCHAR(255) DEFAULT NULL, needs_proxy VARCHAR(255) DEFAULT NULL, database_guide VARCHAR(255) DEFAULT NULL, database_guide_link_text VARCHAR(255) DEFAULT NULL, access_policy VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE keyword (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE keyword_database (keyword_id INTEGER NOT NULL, database_id INTEGER NOT NULL, PRIMARY KEY(keyword_id, database_id))');
        $this->addSql('CREATE INDEX IDX_5838CB7F115D4552 ON keyword_database (keyword_id)');
        $this->addSql('CREATE INDEX IDX_5838CB7FF0AA09DB ON keyword_database (database_id)');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE type_database (type_id INTEGER NOT NULL, database_id INTEGER NOT NULL, PRIMARY KEY(type_id, database_id))');
        $this->addSql('CREATE INDEX IDX_4AC21D2C54C8C93 ON type_database (type_id)');
        $this->addSql('CREATE INDEX IDX_4AC21D2F0AA09DB ON type_database (database_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE "database"');
        $this->addSql('DROP TABLE keyword');
        $this->addSql('DROP TABLE keyword_database');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_database');
    }
}
