<?php

namespace ProduitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * consultProduit
 *
 * @ORM\Table(name="consult_produit")
 * @ORM\Entity(repositoryClass="ProduitBundle\Repository\consultProduitRepository")
 */
class consultProduit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *@ORM\ManyToOne(targetEntity="ProduitBundle\Entity\Produit")
     *@ORM\JoinColumn(name="idProduit",referencedColumnName="id_produit")
     */
    private $idProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="idUtilisateur", type="integer")
     */
    private $idUtilisateur;
    /**
     * @var integer
     *
     * @ORM\Column(name="nomconsulteur", type="string", length=255,nullable=true)
     */
    private $nomconsulteur;
    /**
     * @var int
     *
     * @ORM\Column(name="consulter", type="integer")
     */
    private $consulter;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNomconsulteur()
    {
        return $this->nomconsulteur;
    }

    /**
     * @param int $nomconsulteur
     */
    public function setNomconsulteur($nomconsulteur)
    {
        $this->nomconsulteur = $nomconsulteur;
    }



    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     *
     * @return consultProduit
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    /**
     * Get idUtilisateur
     *
     * @return int
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * Set consulter
     *
     * @param integer $consulter
     *
     * @return consultProduit
     */
    public function setConsulter($consulter)
    {
        $this->consulter = $consulter;

        return $this;
    }

    /**
     * Get consulter
     *
     * @return int
     */
    public function getConsulter()
    {
        return $this->consulter;
    }

    /**
     * @return mixed
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * @param mixed $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }

}

