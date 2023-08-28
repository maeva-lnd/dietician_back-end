<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615124411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table diet_user';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diet_user (diet_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_778B024CE1E13ACE (diet_id), INDEX IDX_778B024CA76ED395 (user_id), PRIMARY KEY(diet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE diet_user ADD CONSTRAINT FK_778B024CE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE diet_user ADD CONSTRAINT FK_778B024CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet_user DROP FOREIGN KEY FK_778B024CE1E13ACE');
        $this->addSql('ALTER TABLE diet_user DROP FOREIGN KEY FK_778B024CA76ED395');
        $this->addSql('DROP TABLE diet_user');
    }
}
