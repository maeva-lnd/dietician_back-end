<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615124217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table allergen_user';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergen_user (allergen_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_65D6B1CD6E775A4A (allergen_id), INDEX IDX_65D6B1CDA76ED395 (user_id), PRIMARY KEY(allergen_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergen_user ADD CONSTRAINT FK_65D6B1CD6E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE allergen_user ADD CONSTRAINT FK_65D6B1CDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allergen_user DROP FOREIGN KEY FK_65D6B1CD6E775A4A');
        $this->addSql('ALTER TABLE allergen_user DROP FOREIGN KEY FK_65D6B1CDA76ED395');
        $this->addSql('DROP TABLE allergen_user');
    }
}
