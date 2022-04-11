<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411141640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operations ADD client_id INT NOT NULL, ADD categories_id INT NOT NULL, ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_2814534819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_28145348A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_2814534867B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_2814534819EB6921 ON operations (client_id)');
        $this->addSql('CREATE INDEX IDX_28145348A21214B7 ON operations (categories_id)');
        $this->addSql('CREATE INDEX IDX_2814534867B3B43D ON operations (users_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operations DROP FOREIGN KEY FK_2814534819EB6921');
        $this->addSql('ALTER TABLE operations DROP FOREIGN KEY FK_28145348A21214B7');
        $this->addSql('ALTER TABLE operations DROP FOREIGN KEY FK_2814534867B3B43D');
        $this->addSql('DROP INDEX IDX_2814534819EB6921 ON operations');
        $this->addSql('DROP INDEX IDX_28145348A21214B7 ON operations');
        $this->addSql('DROP INDEX IDX_2814534867B3B43D ON operations');
        $this->addSql('ALTER TABLE operations DROP client_id, DROP categories_id, DROP users_id');
    }
}
