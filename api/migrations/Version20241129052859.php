<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129052859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, country_id INT NOT NULL, city_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, age INT NOT NULL, birthday DATE NOT NULL, INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY city_ibfk_1');
        $this->addSql('ALTER TABLE countrylanguage DROP FOREIGN KEY countryLanguage_ibfk_1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (ID INT AUTO_INCREMENT NOT NULL, Name CHAR(35) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CountryCode CHAR(3) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, District CHAR(20) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, Population INT DEFAULT 0 NOT NULL, INDEX CountryCode (CountryCode), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE country (Code CHAR(3) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, Name CHAR(52) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, Continent VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'Asia\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, Region CHAR(26) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, SurfaceArea NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, IndepYear SMALLINT DEFAULT NULL, Population INT DEFAULT 0 NOT NULL, LifeExpectancy NUMERIC(3, 1) DEFAULT NULL, GNP NUMERIC(10, 2) DEFAULT NULL, GNPOld NUMERIC(10, 2) DEFAULT NULL, LocalName CHAR(45) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, GovernmentForm CHAR(45) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, HeadOfState CHAR(60) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, Capital INT DEFAULT NULL, Code2 CHAR(2) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(Code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE countrylanguage (CountryCode CHAR(3) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, Language CHAR(30) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, IsOfficial VARCHAR(5) CHARACTER SET utf8mb4 DEFAULT \'F\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`, Percentage NUMERIC(4, 1) DEFAULT \'0.0\' NOT NULL, INDEX CountryCode (CountryCode), PRIMARY KEY(CountryCode, Language)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT city_ibfk_1 FOREIGN KEY (CountryCode) REFERENCES country (Code) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE countrylanguage ADD CONSTRAINT countryLanguage_ibfk_1 FOREIGN KEY (CountryCode) REFERENCES country (Code) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
