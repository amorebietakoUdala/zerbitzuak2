<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127104850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('update erantzun.user set roles="[\"ROLE_KANPOKO_TEKNIKARIA\"]" where roles="a:1:{i:0;s:23:\"ROLE_KANPOKO_TEKNIKARIA\";}"');
        $this->addSql('update erantzun.user set roles="[\"ROLE_ARDURADUNA\"]" where roles="a:1:{i:0;s:15:\"ROLE_ARDURADUNA\";}"');
        $this->addSql('update erantzun.user set roles="[\"ROLE_INFORMATZAILEA\"]" where roles="a:1:{i:0;s:19:\"ROLE_INFORMATZAILEA\";}"');
        $this->addSql('update erantzun.user set roles="[\"ROLE_ADMIN\"]" where roles="a:1:{i:0;s:10:\"ROLE_ADMIN\";}"');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('update erantzun.user set roles="a:1:{i:0;s:23:\"ROLE_KANPOKO_TEKNIKARIA\";}" where roles="[\"ROLE_KANPOKO_TEKNIKARIA\"]"');
        $this->addSql('update erantzun.user set roles="a:1:{i:0;s:15:\"ROLE_ARDURADUNA\";}" where roles="[\"ROLE_ARDURADUNA\"]"');
        $this->addSql('update erantzun.user set roles="a:1:{i:0;s:19:\"ROLE_INFORMATZAILEA\";}" where roles="[\"ROLE_INFORMATZAILEA\"]"');
        $this->addSql('update erantzun.user set roles="a:1:{i:0;s:10:\"ROLE_ADMIN\";}" where roles="[\"ROLE_ADMIN\"]"');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
