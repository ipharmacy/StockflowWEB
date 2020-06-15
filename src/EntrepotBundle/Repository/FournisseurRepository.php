<?php

namespace EntrepotBundle\Repository;

/**
 * FournisseurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FournisseurRepository extends \Doctrine\ORM\EntityRepository
{

    public function get_tous_fournisseurs($entrepot )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT r.Nom,r.Prenom,r.type,r.produit,r.Quantite ,r.id FROM fournisseur r INNER JOIN entrepot e  where (e.id=r.Id_entrepot) and (e.id=$entrepot) ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_tous_fournisseursseloniduser($id_user )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM fournisseur r where $id_user=r.Id_user ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_fournisseur_supprimer($id )
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM fournisseur r INNER JOIN entrepot e  where (e.id=r.Id_entrepot) and (r.id=$id) ";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }



}
