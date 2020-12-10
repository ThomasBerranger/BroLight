<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210164134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_relationship DROP FOREIGN KEY FK_A0C838A21816E3A3');
        $this->addSql('ALTER TABLE user_relationship DROP FOREIGN KEY FK_A0C838A2AC24F853');
        $this->addSql('DROP INDEX UNIQ_A0C838A2AC24F853 ON user_relationship');
        $this->addSql('DROP INDEX UNIQ_A0C838A21816E3A3 ON user_relationship');
        $this->addSql('ALTER TABLE user_relationship DROP follower_id, DROP following_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_relationship ADD follower_id INT NOT NULL, ADD following_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A21816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A2AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0C838A2AC24F853 ON user_relationship (follower_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0C838A21816E3A3 ON user_relationship (following_id)');
    }
}
