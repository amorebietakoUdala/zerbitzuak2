<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112080434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE Table user as select * from erabiltzaileak');
        $this->addSql('ALTER TABLE user ADD firstName VARCHAR(255) NOT NULL, ADD activated TINYINT(1) DEFAULT \'1\' NOT NULL, ADD lastLogin DATETIME DEFAULT NULL, DROP username_canonical, DROP email_canonical, DROP salt, DROP confirmation_token, DROP password_requested_at, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE user set firstName = izena');
        $this->addSql('UPDATE user set lastLogin = last_login');
        $this->addSql('UPDATE user set activated = enabled');
	$this->addSql('ALTER TABLE user ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B932554 FOREIGN KEY (enpresa_id) REFERENCES enpresak (id)');
	$this->addSql('ALTER TABLE erantzunak DROP FOREIGN KEY FK_56263887905C16CA');
        $this->addSql('ALTER TABLE erantzunak ADD CONSTRAINT FK_56263887905C16CA FOREIGN KEY (erantzulea_id) REFERENCES user (id)');
	$this->addSql('ALTER TABLE eskakizunak DROP FOREIGN KEY FK_380BE00051798FC8');
        $this->addSql('ALTER TABLE eskakizunak ADD CONSTRAINT FK_380BE00051798FC8 FOREIGN KEY (norkErreklamatua_id) REFERENCES user (id)');
	$this->addSql('ALTER TABLE eskakizunak DROP FOREIGN KEY FK_380BE000412DF9F6');
        $this->addSql('ALTER TABLE eskakizunak ADD CONSTRAINT FK_380BE000412DF9F6 FOREIGN KEY (norkInformatua_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE INDEX IDX_8D93D6496B932554 ON user (enpresa_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE erantzunak DROP FOREIGN KEY FK_56263887905C16CA');
        $this->addSql('ALTER TABLE eskakizunak DROP FOREIGN KEY FK_380BE00051798FC8');
        $this->addSql('ALTER TABLE eskakizunak DROP FOREIGN KEY FK_380BE000412DF9F6');
        $this->addSql('ALTER TABLE user MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B932554');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('DROP INDEX IDX_8D93D6496B932554 ON user');
        $this->addSql('ALTER TABLE user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user ADD username_canonical VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD email_canonical VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD enabled TINYINT(1) NOT NULL, ADD salt VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD confirmation_token VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD password_requested_at DATETIME DEFAULT NULL, DROP firstName, DROP activated, CHANGE id id INT DEFAULT 0 NOT NULL, CHANGE email email VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE lastlogin last_login DATETIME DEFAULT NULL');
    }
}
