<?php

namespace RecrutementBundle\Repository;

/**
 * RecrutementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RecrutementRepository extends \Doctrine\ORM\EntityRepository
{
    public function RechercheRecrutement($nom,$prenom)
    {
        $query = $this->getEntityManager()->createQuery("Select p FROM RecrutementBundle\Entity\Recrutement p WHERE p.prenom=:prenom and p.nom=:nom")
            ->setParameter('prenom',$prenom)
            ->setParameter('nom',$nom);
        return $query->getOneOrNullResult();
    }
}
