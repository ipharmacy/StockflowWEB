<?php

namespace ProduitBundle\Repository;

/**
 * consultProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class consultProduitRepository extends \Doctrine\ORM\EntityRepository
{
    public function recupererConsulter($idUser)
    {

        return $this->getEntityManager()
            ->createQuery('SELECT p FROM ProduitBundle:consultProduit p WHERE p.idUtilisateur=:idUser AND p.consulter=1')
            ->setParameter('idUser',$idUser)
            ->getResult();
    }
}
