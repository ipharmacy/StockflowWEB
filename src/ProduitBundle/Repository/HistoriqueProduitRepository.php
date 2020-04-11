<?php


namespace ProduitBundle\Repository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;


class HistoriqueProduitRepository extends EntityRepository
{

    public function deleteAll(){
        /*return $this->getEntityManager()
            ->createQuery('select p.idCategorie count(p.idCategorie) from ProduitBundle:Produit p GROUP BY p.idCategorie')
            ->getResult();
        */
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'TRUNCATE TABLE `historique_produit`';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return true;


    }
}