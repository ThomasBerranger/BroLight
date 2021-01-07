<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107094855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C72A475A3');
        $this->addSql('DROP INDEX IDX_9474526C72A475A3 ON comment');
        $this->addSql('ALTER TABLE comment DROP related_comment_id');
        $this->addSql('ALTER TABLE view ADD comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8EF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEFDAB8EF8697D13 ON view (comment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD related_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C72A475A3 FOREIGN KEY (related_comment_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_9474526C72A475A3 ON comment (related_comment_id)');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8EF8697D13');
        $this->addSql('DROP INDEX UNIQ_FEFDAB8EF8697D13 ON view');
        $this->addSql('ALTER TABLE view DROP comment_id');
    }
}
