<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217092539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE from tblProductData');
        $this->addSql('ALTER TABLE tblProductData ADD dtmDiscontinued DATETIME DEFAULT NULL, DROP blnDiscontinued, DROP blnIsDeleted, CHANGE dtmAdded dtmAdded timestamp default CURRENT_TIMESTAMP not null, CHANGE stmTimestamp stmTimestamp timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData ADD blnDiscontinued TINYINT(1) DEFAULT \'0\' NOT NULL, ADD blnIsDeleted TINYINT(1) DEFAULT \'0\' NOT NULL, DROP dtmDiscontinued, CHANGE dtmAdded dtmAdded DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE stmTimestamp stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
