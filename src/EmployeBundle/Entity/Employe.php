<?php

namespace EmployeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension ;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Employe
 *
 * @ORM\Table(name="employe")
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\EmployeRepository")
 */
class Employe
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
     * @var string
     *@Assert\NotBlank(
     *     message="Le champ nom ne doit pas etre vide "
     * )
     * @ORM\Column(name="nom", type="string", length=255)
     *
     */
    private $nom;

    /**
     * @var string
     *@Assert\NotBlank(
     *     message="Le champ prenom ne doit pas etre vide "
     * )
     * @ORM\Column(name="prenom", type="string", length=255)
     *
     */
    private $prenom;

    /**
     *
     * @Assert\NotBlank(
     *     message="Le champ email ne doit pas etre vide "
     * )
     * @Assert\Email(
     *     message="l'email '{{ value }}' n'est pas valide "
     * )
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     *
     * @Assert\NotBlank(
     *     message="le champ cin doit contenir 8 chiffres"
     * )
     * @var int
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     minMessage="Le cin doit avoir 8 chiffres ",
     *     maxMessage="le cin doit avoir 8 chiffres "
     * )
     *
     * @ORM\Column(name="cin", type="integer")
     */
    private $cin;

    /**
     * @Assert\NotBlank(
     *     message="Le champ date ne doit pas etre vide "
     * )
     * @Assert\LessThan(
     *     value="-18 years",
     *     message="L'employe doit avoir au moins 18 ans "
     * )
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date")
     *
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="poste", type="string", length=255)
     */
    private $poste;

    /**
     * @var int
     *
     * @ORM\Column(name="numTel", type="integer")
     */
    private $numTel;

    /**
     * @var bool
     *
     * @ORM\Column(name="salaire", type="float")
     */
    private $salaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRecrutement", type="datetime")
     */
    private $dateRecrutement;

    /**
     * @var bool
     *
     * @ORM\Column(name="conge", type="boolean", nullable=false)
     */
    private $conge;

    /**
     * @var int
     *
     * @ORM\Column(name="idUtilisateur", type="integer")
     */
    private $idUtilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Employe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Employe
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Employe
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set cin
     *
     * @param integer $cin
     *
     * @return Employe
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return int
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Employe
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set poste
     *
     * @param string $poste
     *
     * @return Employe
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return string
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set numTel
     *
     * @param integer $numTel
     *
     * @return Employe
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel
     *
     * @return int
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * Set salaire
     *
     * @param float $salaire
     *
     * @return Employe
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return float
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set dateRecrutement
     *
     * @param \DateTime $dateRecrutement
     *
     * @return Employe
     */
    public function setDateRecrutement($dateRecrutement)
    {
        $this->dateRecrutement = $dateRecrutement;

        return $this;
    }

    /**
     * Get dateRecrutement
     *
     * @return \DateTime
     */
    public function getDateRecrutement()
    {
        return $this->dateRecrutement;
    }

    /**
     * Set conge
     *
     * @param float $conge
     *
     * @return Employe
     */
    public function setConge($conge)
    {
        $this->conge = $conge;

        return $this;
    }

    /**
     * Get conge
     *
     * @return float
     */
    public function getConge()
    {
        return $this->conge;
    }

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     *
     * @return Employe
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









}

