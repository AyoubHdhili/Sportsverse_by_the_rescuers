<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306195520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE codepromo (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, valeur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cv_activite DROP FOREIGN KEY FK_295AB0D19B0F88B1');
        $this->addSql('ALTER TABLE cv_activite DROP FOREIGN KEY FK_295AB0D1CFE419E2');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F347EFB');
        $this->addSql('DROP TABLE cv_activite');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE activite ADD cv_id INT NOT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_B8755515CFE419E2 ON activite (cv_id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande ADD code_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D27DAFE17 FOREIGN KEY (code_id) REFERENCES codepromo (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D27DAFE17 ON commande (code_id)');
        $this->addSql('ALTER TABLE produit DROP slug');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL, DROP roles, DROP is_verified, DROP is_banned, CHANGE num_tel num_tel INT NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D27DAFE17');
        $this->addSql('CREATE TABLE cv_activite (cv_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_295AB0D1CFE419E2 (cv_id), INDEX IDX_295AB0D19B0F88B1 (activite_id), PRIMARY KEY(cv_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, user_id INT DEFAULT NULL, date_creation DATETIME DEFAULT NULL, comment LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nbre INT NOT NULL, INDEX IDX_794381C6F347EFB (produit_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cv_activite ADD CONSTRAINT FK_295AB0D19B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cv_activite ADD CONSTRAINT FK_295AB0D1CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('DROP TABLE codepromo');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515CFE419E2');
        $this->addSql('DROP INDEX IDX_B8755515CFE419E2 ON activite');
        $this->addSql('ALTER TABLE activite DROP cv_id');
        $this->addSql('DROP INDEX IDX_6EEAA67D27DAFE17 ON commande');
        $this->addSql('ALTER TABLE commande ADD user_id INT NOT NULL, DROP code_id');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('ALTER TABLE produit ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD is_verified TINYINT(1) NOT NULL, ADD is_banned TINYINT(1) NOT NULL, DROP role, CHANGE num_tel num_tel VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON `user` (email)');
    }
}
