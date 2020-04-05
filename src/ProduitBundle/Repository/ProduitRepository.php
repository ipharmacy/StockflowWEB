<?php


namespace ProduitBundle\Repository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;


class ProduitRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ProduitBundle:Produit p ORDER BY p.nom ASC'
            )
            ->getResult();
    }
    public function findAllOrderedByPrix()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ProduitBundle:Produit p ORDER BY p.prix ASC'
            )
            ->getResult();
    }
    public function findAllOrderedByDate()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ProduitBundle:Produit p ORDER BY p.date ASC'
            )
            ->getResult();
    }
    public function findAllOrderedByCategorie()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ProduitBundle:Produit p ORDER BY p.idCategorie ASC'
            )
            ->getResult();
    }
    public function search($chaine)
    {
        return $this->getEntityManager()
            ->createQuery('select p from ProduitBundle:Produit p WHERE  CONCAT(p.nom,p.prix,p.date) LIKE :chaine'
            )->setParameter('chaine','%'.$chaine.'%')
            ->getResult();

    }
    public function searchBack($chaine,$idUser)
    {
        return $this->getEntityManager()
            ->createQuery('select p from ProduitBundle:Produit p WHERE p.idUtilisateur=:idUser AND CONCAT(p.nom,p.prix,p.date) LIKE :chaine'
            )->setParameter('chaine','%'.$chaine.'%')
            ->setParameter('idUser',$idUser)
            ->getResult();

    }
    public function statProduit(){
        /*return $this->getEntityManager()
            ->createQuery('select p.idCategorie count(p.idCategorie) from ProduitBundle:Produit p GROUP BY p.idCategorie')
            ->getResult();
        */
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT produit.idCategorie, count(idCategorie) AS somme FROM produit group by idCategorie';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();


    }
}