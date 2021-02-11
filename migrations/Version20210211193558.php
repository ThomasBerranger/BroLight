<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211193558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, accessories_type VARCHAR(255) NOT NULL, avatar_style VARCHAR(255) NOT NULL, clothe_color VARCHAR(255) NOT NULL, clothe_type VARCHAR(255) NOT NULL, eye_type VARCHAR(255) NOT NULL, eyebrow_type VARCHAR(255) NOT NULL, facial_hair_color VARCHAR(255) NOT NULL, facial_hair_type VARCHAR(255) NOT NULL, graphic_type VARCHAR(255) NOT NULL, hair_color VARCHAR(255) NOT NULL, hat_color VARCHAR(255) NOT NULL, mouth_type VARCHAR(255) NOT NULL, skin_color VARCHAR(255) NOT NULL, top_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1677722FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, tmdb_id INT NOT NULL, is_viewed TINYINT(1) DEFAULT NULL, viewed_at DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, commented_at DATETIME DEFAULT NULL, is_spoiler TINYINT(1) DEFAULT NULL, rate INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_AB02B027F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podium (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, first_tmdb_id INT DEFAULT NULL, second_tmdb_id INT DEFAULT NULL, third_tmdb_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_338B4C53F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relationship (id INT AUTO_INCREMENT NOT NULL, user_source_id INT NOT NULL, user_target_id INT NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_200444A095DC9185 (user_source_id), INDEX IDX_200444A0156E8682 (user_target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', slug VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C53F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relationship ADD CONSTRAINT FK_200444A095DC9185 FOREIGN KEY (user_source_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relationship ADD CONSTRAINT FK_200444A0156E8682 FOREIGN KEY (user_target_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FF675F31B');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027F675F31B');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C53F675F31B');
        $this->addSql('ALTER TABLE relationship DROP FOREIGN KEY FK_200444A095DC9185');
        $this->addSql('ALTER TABLE relationship DROP FOREIGN KEY FK_200444A0156E8682');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE opinion');
        $this->addSql('DROP TABLE podium');
        $this->addSql('DROP TABLE relationship');
        $this->addSql('DROP TABLE user');
    }
}
