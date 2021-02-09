<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209175141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE relationship (id INT AUTO_INCREMENT NOT NULL, user_source_id INT NOT NULL, user_target_id INT NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_200444A095DC9185 (user_source_id), INDEX IDX_200444A0156E8682 (user_target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relationship ADD CONSTRAINT FK_200444A095DC9185 FOREIGN KEY (user_source_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE relationship ADD CONSTRAINT FK_200444A0156E8682 FOREIGN KEY (user_target_id) REFERENCES `user` (id)');
        $this->addSql('DROP TABLE user_relationship');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_relationship (id INT AUTO_INCREMENT NOT NULL, user_target_id INT NOT NULL, user_source_id INT NOT NULL, status INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A0C838A2156E8682 (user_target_id), INDEX IDX_A0C838A295DC9185 (user_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A2156E8682 FOREIGN KEY (user_target_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A295DC9185 FOREIGN KEY (user_source_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE relationship');
    }
}
