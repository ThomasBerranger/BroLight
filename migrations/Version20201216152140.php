<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201216152140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8F93B6FC');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F398F93B6FC');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8E8F93B6FC');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP INDEX IDX_9474526C8F93B6FC ON comment');
        $this->addSql('ALTER TABLE comment CHANGE movie_id tmdb_id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_DFEC3F398F93B6FC ON rate');
        $this->addSql('ALTER TABLE rate CHANGE movie_id tmdb_id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_FEFDAB8E8F93B6FC ON view');
        $this->addSql('ALTER TABLE view CHANGE movie_id tmdb_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, tmdb_movie_id INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment CHANGE tmdb_id movie_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_9474526C8F93B6FC ON comment (movie_id)');
        $this->addSql('ALTER TABLE rate CHANGE tmdb_id movie_id INT NOT NULL');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F398F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F398F93B6FC ON rate (movie_id)');
        $this->addSql('ALTER TABLE view CHANGE tmdb_id movie_id INT NOT NULL');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8E8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('CREATE INDEX IDX_FEFDAB8E8F93B6FC ON view (movie_id)');
    }
}
