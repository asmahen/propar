<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419124227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, nom_societe VARCHAR(255) DEFAULT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, adresse LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operations (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, categories_id INT NOT NULL, users_id INT DEFAULT NULL, description LONGTEXT NOT NULL, INDEX IDX_2814534819EB6921 (client_id), INDEX IDX_28145348A21214B7 (categories_id), INDEX IDX_2814534867B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_2814534819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_28145348A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_2814534867B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operations DROP FOREIGN KEY FK_28145348A21214B7');
        $this->addSql('ALTER TABLE operations DROP FOREIGN KEY FK_2814534819EB6921');
        $this->addSql('ALTER TABLE operations DROP FOREIGN KEY FK_2814534867B3B43D');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE operations');
        $this->addSql('DROP TABLE users');
    }
}
