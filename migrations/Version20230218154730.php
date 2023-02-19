<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218154730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EDC2902E0');
        $this->addSql('DROP INDEX IDX_DF7DFD0EDC2902E0 ON seance');
        $this->addSql('ALTER TABLE seance ADD nom_client VARCHAR(255) NOT NULL, CHANGE client_id_id coach_id_id INT NOT NULL, CHANGE durée duree VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E6BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0E6BC6FD7D ON seance (coach_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E6BC6FD7D');
        $this->addSql('DROP INDEX IDX_DF7DFD0E6BC6FD7D ON seance');
        $this->addSql('ALTER TABLE seance ADD durée VARCHAR(255) NOT NULL, DROP duree, DROP nom_client, CHANGE coach_id_id client_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EDC2902E0 FOREIGN KEY (client_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0EDC2902E0 ON seance (client_id_id)');
    }
}
