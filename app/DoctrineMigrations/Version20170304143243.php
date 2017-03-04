<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170304143243 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tracks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genres_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE years_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE singers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tracks (id INT NOT NULL, singer_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, year_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_246D2A2E271FD47C ON tracks (singer_id)');
        $this->addSql('CREATE INDEX IDX_246D2A2E4296D31F ON tracks (genre_id)');
        $this->addSql('CREATE INDEX IDX_246D2A2E40C1FEA7 ON tracks (year_id)');
        $this->addSql('CREATE TABLE genres (id INT NOT NULL, internal_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE years (id INT NOT NULL, internal_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE singers (id INT NOT NULL, internal_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE tracks ADD CONSTRAINT FK_246D2A2E271FD47C FOREIGN KEY (singer_id) REFERENCES singers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tracks ADD CONSTRAINT FK_246D2A2E4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tracks ADD CONSTRAINT FK_246D2A2E40C1FEA7 FOREIGN KEY (year_id) REFERENCES years (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tracks DROP CONSTRAINT FK_246D2A2E4296D31F');
        $this->addSql('ALTER TABLE tracks DROP CONSTRAINT FK_246D2A2E40C1FEA7');
        $this->addSql('ALTER TABLE tracks DROP CONSTRAINT FK_246D2A2E271FD47C');
        $this->addSql('DROP SEQUENCE tracks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genres_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE years_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE singers_id_seq CASCADE');
        $this->addSql('DROP TABLE tracks');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE years');
        $this->addSql('DROP TABLE singers');
    }
}
