<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171102113830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE argazkiak');
        $this->addSql('DROP TABLE profilak');
        $this->addSql('ALTER TABLE eskakizunak ADD noizInformatua DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE argazkiak (id INT AUTO_INCREMENT NOT NULL, eskakizuna_id INT DEFAULT NULL, imageName VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, imageSize INT NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_701823C53C51F06F (eskakizuna_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profilak (id INT AUTO_INCREMENT NOT NULL, izena VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ordena INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eskakizunak DROP noizInformatua');
    }
}
