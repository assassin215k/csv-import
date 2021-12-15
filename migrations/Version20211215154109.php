<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215154109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData ADD blnDiscontinued boolean default false not null, ADD intProductStock INT UNSIGNED NOT NULL, ADD numProductCost NUMERIC(16, 2) UNSIGNED NOT NULL, ADD blnIsDeleted boolean default false not null, DROP dtmDiscontinued, CHANGE dtmAdded dtmAdded timestamp default CURRENT_TIMESTAMP not null, CHANGE stmTimestamp stmTimestamp timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData ADD dtmDiscontinued DATETIME DEFAULT NULL, DROP blnDiscontinued, DROP intProductStock, DROP numProductCost, DROP blnIsDeleted, CHANGE dtmAdded dtmAdded DATETIME DEFAULT NULL, CHANGE stmTimestamp stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
