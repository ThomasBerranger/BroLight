<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201221213914 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, accessories_type VARCHAR(255) NOT NULL, avatar_style VARCHAR(255) NOT NULL, clothe_color VARCHAR(255) NOT NULL, clothe_type VARCHAR(255) NOT NULL, eye_type VARCHAR(255) NOT NULL, eyebrow_type VARCHAR(255) NOT NULL, facial_hair_color VARCHAR(255) NOT NULL, facial_hair_type VARCHAR(255) NOT NULL, graphic_type VARCHAR(255) NOT NULL, hair_color VARCHAR(255) NOT NULL, hat_color VARCHAR(255) NOT NULL, mouth_type VARCHAR(255) NOT NULL, skin_color VARCHAR(255) NOT NULL, top_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1677722FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, related_comment_id INT DEFAULT NULL, tmdb_id INT NOT NULL, content LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C72A475A3 (related_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, tmdb_id INT NOT NULL, rate INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_DFEC3F39F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_relationship (id INT AUTO_INCREMENT NOT NULL, user_target_id INT NOT NULL, user_source_id INT NOT NULL, status INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A0C838A2156E8682 (user_target_id), INDEX IDX_A0C838A295DC9185 (user_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE view (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, tmdb_id INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_FEFDAB8EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C72A475A3 FOREIGN KEY (related_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F39F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A2156E8682 FOREIGN KEY (user_target_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A295DC9185 FOREIGN KEY (user_source_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8EF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C72A475A3');
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F39F675F31B');
        $this->addSql('ALTER TABLE user_relationship DROP FOREIGN KEY FK_A0C838A2156E8682');
        $this->addSql('ALTER TABLE user_relationship DROP FOREIGN KEY FK_A0C838A295DC9185');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8EF675F31B');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_relationship');
        $this->addSql('DROP TABLE view');
    }
}
