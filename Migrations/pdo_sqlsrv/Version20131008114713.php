<?php

namespace Claroline\CoreBundle\Migrations\pdo_sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2013/10/08 11:47:14
 */
class Version20131008114713 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_user 
            ADD picture NVARCHAR(255)
        ");
        $this->addSql("
            ALTER TABLE claro_user 
            ADD description NVARCHAR(255)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_user 
            DROP COLUMN picture
        ");
        $this->addSql("
            ALTER TABLE claro_user 
            DROP COLUMN description
        ");
    }
}