<?php

namespace ProduitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Tests\StringableObject;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="ProduitBundle\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_produit", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduit;

    /**
     * @var string
     * @Assert/NotBlank()
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="ProduitBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="idCategorie",referencedColumnName="id")
     */
    private $idCategorie;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;
    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255, nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255, nullable=false)
     */
    private $img;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_entrepot", type="integer", nullable=false)
     */
    private $idEntrepot;

    /**
     * @var integer
     *
     * @ORM\Column(name="idUtilisateur", type="integer",nullable=false)
     */
    private $idUtilisateur;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="commentaire_file", fileNameProperty="imageName", size="imageSize")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @var integer
     *
     * @ORM\Column(name="archiver", type="integer",nullable=true)
     */
    private $archiver;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbvue", type="integer",nullable=true)
     */
    private $nbvue;

    /**
     * @return int
     */
    public function getNbvue()
    {
        return $this->nbvue;
    }

    /**
     * @param int $nbvue
     */
    public function setNbvue($nbvue)
    {
        $this->nbvue = $nbvue;
    }


    /**
     * @return int
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * @param int $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    /**
     * @return mixed
     */
    public function getIdC()
    {
        return $this->idCategorie;
    }

    /**
     * @param mixed $idCategorie
     */
    public function setIdC($idCategorie)
    {
        $this->idCategorie = $idCategorie;
    }

    /**
     * @return int
     */
    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    /**
     * @param int $idCategorie
     */
    public function setIdCategorie($idCategorie)
    {
        $this->idCategorie = $idCategorie;
    }



    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * @return int
     */
    public function getIdEntrepot()
    {
        return $this->idEntrepot;
    }

    /**
     * @param int $idEntrepot
     */
    public function setIdEntrepot($idEntrepot)
    {
        $this->idEntrepot = $idEntrepot;
    }

    /**
     * @return mixed
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * @param mixed $idUtilisateur
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }



    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

    }


    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }


    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return int
     */
    public function getArchiver()
    {
        return $this->archiver;
    }

    /**
     * @param int $archiver
     */
    public function setArchiver($archiver)
    {
        $this->archiver = $archiver;
    }


}

