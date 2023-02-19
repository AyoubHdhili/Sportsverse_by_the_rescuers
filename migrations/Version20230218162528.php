<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218162528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP INDEX UNIQ_DF7DFD0EC4598A51, ADD INDEX IDX_DF7DFD0EC4598A51 (emplacement_id)');
        $this->addSql('ALTER TABLE seance CHANGE emplacement_id emplacement_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP INDEX IDX_DF7DFD0EC4598A51, ADD UNIQUE INDEX UNIQ_DF7DFD0EC4598A51 (emplacement_id)');
        $this->addSql('ALTER TABLE seance CHANGE emplacement_id emplacement_id INT NOT NULL');
    }
}
