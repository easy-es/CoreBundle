<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Claroline\CoreBundle\Entity\Competence\Competence;
use Claroline\CoreBundle\Entity\Workspace\Workspace;
use Claroline\CoreBundle\Entity\Competence\CompetenceNode;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CompetenceRepository extends NestedTreeRepository {

    public function excludeHierarchyNode(CompetenceNode $cpt)
    {
        $dql = "
        SELECT c.name as name , cp.id as id 
        FROM Claroline\CoreBundle\Entity\Competence\Competence c,
        	 Claroline\CoreBundle\Entity\Competence\CompetenceNode cp
        WHERE 
        	c.id = cp.competence
        	AND cp.id NOT IN (
        		SELECT cpt
        		FROM Claroline\CoreBundle\Entity\Competence\CompetenceNode cpt
         		WHERE cpt.lft <= :lft
        		AND cpt.root = :root
        	)
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('lft', $cpt->getLft());
        $query->setParameter('root',$cpt->getRoot());

        return $query->getResult();
    }

    public function getRootCpt()
    {
    	$dql = "
    	SELECT c.name as name , ch.id as id 
        FROM Claroline\CoreBundle\Entity\Competence\Competence c,
        	 Claroline\CoreBundle\Entity\Competence\CompetenceNode ch
        WHERE
        	c.id = ch.competence
        	AND ch.parent IS NULL
        ";
    	$query = $this->_em->createQuery($dql);

    	return $query->getResult();
    }

    public function getRootCptWithWorkspace(Workspace $workspace)
    {
        $dql = "
        SELECT DISTINCT c.name as name , ch.id as id 
        FROM Claroline\CoreBundle\Entity\Competence\Competence c,
             Claroline\CoreBundle\Entity\Competence\CompetenceNode ch
        WHERE ( c.workspace = :workspace OR c.workspace is NULL ) 
        AND EXISTS
            (
                SELECT cpth FROM Claroline\CoreBundle\Entity\Competence\CompetenceNode cpth
                WHERE ch.parent IS NULL 
                )
        AND c.id = ch.competence
        ";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspace', $workspace);
        
        return $query->getResult();
    }

    public function findHiearchyNameById(CompetenceNode $competence)
    {
        $dql = "
    	SELECT c.name as name , ch.id as id
        FROM Claroline\CoreBundle\Entity\Competence\CompetenceNode ch
        JOIN ch.competence c WHERE ch.root = :root
        ";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('root',$competence->getRoot());

        return $query->getResult();
    }

    public function findHiearchyById(CompetenceNode $competence)
    {
        $dql = "
    	SELECT c.name as name, c.description, c.score as score,c.code as code, ch.id as id
        FROM Claroline\CoreBundle\Entity\Competence\CompetenceNode ch
        JOIN ch.competence c WHERE ch.root = :root
        ";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('root',$competence->getRoot());

        return $query->getResult();
    }

    public function findFullHiearchyById(array $roots)
    {
        $dql = "
        SELECT DISTINCT c
        FROM Claroline\CoreBundle\Entity\Competence\Competence c
        WHERE EXISTS
            (
                SELECT ch FROM Claroline\CoreBundle\Entity\Competence\CompetenceNode ch
                WHERE ch.root IN (:roots) AND ch.competence = c 
            )
        ";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('roots', $roots);

        return $query->getResult();
    }

    public function findByWorkspace($workspace)
    {
        $dql = "
        SELECT c FROM ClarolineCoreBundle:Competence\Competence c
        WHERE c.workspace = :workspace 
        ";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('workspace', $workspace);

        return $query->getResult();
    }    
} 