<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210171536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64915BF9993');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A8B65994');
        $this->addSql('DROP INDEX IDX_8D93D64915BF9993 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649A8B65994 ON user');
        $this->addSql('ALTER TABLE user DROP followers_id, DROP followings_id');
        $this->addSql('ALTER TABLE user_relationship ADD following_id INT NOT NULL, ADD follower_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A21816E3A3 FOREIGN KEY (following_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_relationship ADD CONSTRAINT FK_A0C838A2AC24F853 FOREIGN KEY (follower_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_A0C838A21816E3A3 ON user_relationship (following_id)');
        $this->addSql('CREATE INDEX IDX_A0C838A2AC24F853 ON user_relationship (follower_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` ADD followers_id INT DEFAULT NULL, ADD followings_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64915BF9993 FOREIGN KEY (followers_id) REFERENCES user_relationship (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649A8B65994 FOREIGN KEY (followings_id) REFERENCES user_relationship (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64915BF9993 ON `user` (followers_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A8B65994 ON `user` (followings_id)');
        $this->addSql('ALTER TABLE user_relationship DROP FOREIGN KEY FK_A0C838A21816E3A3');
        $this->addSql('ALTER TABLE user_relationship DROP FOREIGN KEY FK_A0C838A2AC24F853');
        $this->addSql('DROP INDEX IDX_A0C838A21816E3A3 ON user_relationship');
        $this->addSql('DROP INDEX IDX_A0C838A2AC24F853 ON user_relationship');
        $this->addSql('ALTER TABLE user_relationship DROP following_id, DROP follower_id');
    }
}
