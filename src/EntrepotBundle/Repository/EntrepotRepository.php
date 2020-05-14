<?php

namespace EntrepotBundle\Repository;

/**
 * EntrepotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntrepotRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllOrderedByRating( )
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM entrepot order by rating DESC  ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllByiduser($id_user )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM entrepot e where (e.idUtilisateur=$id_user)  ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllOrderedBySurface( )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM entrepot e order by e.largitude*e.longitude DESC ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllOrderedByName( )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM entrepot e order by e.nom ASC ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllOrderedByVues( )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM entrepot e order by e.vues DESC";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function findAllOrderedByAdresse( )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM entrepot e order by e.adresse DESC ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function findAllOrderedByRatingByUser($iduser)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM entrepot e  where $iduser=e.idUtilisateur order by e.rating DESC  ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
