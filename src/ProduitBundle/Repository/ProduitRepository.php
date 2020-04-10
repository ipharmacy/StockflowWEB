<?php


namespace ProduitBundle\Repository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;


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
    public function statProduit($id){
        /*return $this->getEntityManager()
            ->createQuery('select p.idCategorie count(p.idCategorie) from ProduitBundle:Produit p GROUP BY p.idCategorie')
            ->getResult();
        */
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT produit.idCategorie, count(idCategorie) AS somme FROM produit  where idUtilisateur='$id' group by idCategorie";

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();


    }
    public function findTopConsulted()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ProduitBundle:Produit p ORDER BY p.nbvue DESC '
            )->setMaxResults(6)
            ->getResult();
    }
   /* public function recupererNbConsulter($idUser)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(p) FROM ProduitBundle:Produit p WHERE p.idUtilisateur=:idUser '
            )->setParameter('idUser',$idUser)
            ->getResult();
    }*/
    public function recupererNbConsulter($idUser)
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT count(p) FROM ProduitBundle:consultProduit p WHERE p.idUtilisateur='$idUser' AND p.consulter=1");

        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

    }
    public function getproduit(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM produit';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {

        }

        $stmt->execute();
        return $stmt->fetchAll();
    }



}