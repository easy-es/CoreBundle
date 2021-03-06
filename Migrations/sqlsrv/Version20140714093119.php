<?php

namespace Claroline\CoreBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/07/14 09:31:21
 */
class Version20140714093119 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE claro_competence (
                id INT IDENTITY NOT NULL, 
                workspace_id INT, 
                name NVARCHAR(255) NOT NULL, 
                description VARCHAR(MAX), 
                score INT NOT NULL, 
                isPlatform BIT, 
                code NVARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_F65DE32582D40A1F ON claro_competence (workspace_id)
        ");
        $this->addSql("
            CREATE TABLE claro_competence_users (
                id INT IDENTITY NOT NULL, 
                competence_id INT, 
                user_id INT NOT NULL, 
                score INT NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_2E80B8E215761DAB ON claro_competence_users (competence_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_2E80B8E2A76ED395 ON claro_competence_users (user_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX competence_user_unique ON claro_competence_users (competence_id, user_id) 
            WHERE competence_id IS NOT NULL 
            AND user_id IS NOT NULL
        ");
        $this->addSql("
            CREATE TABLE claro_competence_hierarchy (
                id INT IDENTITY NOT NULL, 
                competence_id INT NOT NULL, 
                parent_id INT, 
                root INT, 
                lft INT NOT NULL, 
                lvl INT NOT NULL, 
                rgt INT NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_D4A415FD15761DAB ON claro_competence_hierarchy (competence_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_D4A415FD727ACA70 ON claro_competence_hierarchy (parent_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX competence_hrch_unique ON claro_competence_hierarchy (competence_id, parent_id) 
            WHERE competence_id IS NOT NULL 
            AND parent_id IS NOT NULL
        ");
        $this->addSql("
            ALTER TABLE claro_competence 
            ADD CONSTRAINT FK_F65DE32582D40A1F FOREIGN KEY (workspace_id) 
            REFERENCES claro_workspace (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE claro_competence_users 
            ADD CONSTRAINT FK_2E80B8E215761DAB FOREIGN KEY (competence_id) 
            REFERENCES claro_competence (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE claro_competence_users 
            ADD CONSTRAINT FK_2E80B8E2A76ED395 FOREIGN KEY (user_id) 
            REFERENCES claro_user (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE claro_competence_hierarchy 
            ADD CONSTRAINT FK_D4A415FD15761DAB FOREIGN KEY (competence_id) 
            REFERENCES claro_competence (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE claro_competence_hierarchy 
            ADD CONSTRAINT FK_D4A415FD727ACA70 FOREIGN KEY (parent_id) 
            REFERENCES claro_competence_hierarchy (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_competence_users 
            DROP CONSTRAINT FK_2E80B8E215761DAB
        ");
        $this->addSql("
            ALTER TABLE claro_competence_hierarchy 
            DROP CONSTRAINT FK_D4A415FD15761DAB
        ");
        $this->addSql("
            ALTER TABLE claro_competence_hierarchy 
            DROP CONSTRAINT FK_D4A415FD727ACA70
        ");
        $this->addSql("
            DROP TABLE claro_competence
        ");
        $this->addSql("
            DROP TABLE claro_competence_users
        ");
        $this->addSql("
            DROP TABLE claro_competence_hierarchy
        ");
    }
}