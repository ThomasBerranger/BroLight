<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210170524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD followers_id INT DEFAULT NULL, ADD followings_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64915BF9993 FOREIGN KEY (followers_id) REFERENCES user_relationship (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A8B65994 FOREIGN KEY (followings_id) REFERENCES user_relationship (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64915BF9993 ON user (followers_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A8B65994 ON user (followings_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64915BF9993');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A8B65994');
        $this->addSql('DROP INDEX IDX_8D93D64915BF9993 ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D649A8B65994 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP followers_id, DROP followings_id');
    }
}
