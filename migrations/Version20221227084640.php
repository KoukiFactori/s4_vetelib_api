<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227084640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, lastname, firstname, email, phone, birthdate, city, zipcode, address, discr, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, city VARCHAR(60) NOT NULL, zipcode VARCHAR(20) NOT NULL, address VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, lastname, firstname, email, phone, birthdate, city, zipcode, address, discr, roles, password) SELECT id, lastname, firstname, email, phone, birthdate, city, zipcode, address, discr, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, lastname, firstname, email, roles, password, phone, birthdate, city, zipcode, address, discr FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, birthdate DATE NOT NULL, city VARCHAR(60) NOT NULL, zipcode VARCHAR(20) NOT NULL, address VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, lastname, firstname, email, roles, password, phone, birthdate, city, zipcode, address, discr) SELECT id, lastname, firstname, email, roles, password, phone, birthdate, city, zipcode, address, discr FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
