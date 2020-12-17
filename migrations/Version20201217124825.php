<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217124825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, accessories_type VARCHAR(255) NOT NULL, avatar_style VARCHAR(255) NOT NULL, clothe_color VARCHAR(255) NOT NULL, clothe_type VARCHAR(255) NOT NULL, eye_type VARCHAR(255) NOT NULL, eyebrow_type VARCHAR(255) NOT NULL, facial_hair_color VARCHAR(255) NOT NULL, facial_hair_type VARCHAR(255) NOT NULL, graphic_type VARCHAR(255) NOT NULL, hair_color VARCHAR(255) NOT NULL, hat_color VARCHAR(255) NOT NULL, mouth_type VARCHAR(255) NOT NULL, skin_color VARCHAR(255) NOT NULL, top_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1677722FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avatar');
    }
}
