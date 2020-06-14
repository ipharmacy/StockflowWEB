<?php

namespace BlogBundle\Repository;

/**
 * BlogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p 
        FROM BlogBundle:Blog p
        WHERE p.sujet LIKE :str'

            )->setParameter('str', '%'.$str.'%')->getResult();
    }
    public function findByiduser($id_user )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM blog r where $id_user=r.Id ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
