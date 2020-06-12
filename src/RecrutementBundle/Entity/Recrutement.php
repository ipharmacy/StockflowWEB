<?php

namespace RecrutementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Recrutement
 *
 * @ORM\Table(name="recrutement")
 * @ORM\Entity(repositoryClass="RecrutementBundle\Repository\RecrutementRepository")
 */
class Recrutement
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
     *
     * @ORM\Column(name="nom", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="le champ nom ne doit pas etre vide"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank(
     *     message="le champ prenom ne doit pas etre vide"
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le champ Email ne doit pas etre vide "
     * )
     * @Assert\Email(
     *     message="l'email {{ value }} n'est pas valide"
     * )
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="integer")
     * @Assert\NotBlank(
     *     message="le champ cin ne doit pas etre vide"
     * )
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     minMessage="Le cin doit avoir 8 chiffres ",
     *     maxMessage="le cin doit avoir 8 chiffres "
     * )
     */
    private $cin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date")
     * @Assert\NotBlank(
     *     message="le champ date de naissance ne doit pas etre vide"
     * )
     * @Assert\LessThan(
     *     value="-18 years",
     *     message="vous devez avoir 18 ans pour pouvoir postuler a un job dans Stockflow"
     * )
     */
    private $dateNaissance;

    /**
     * @var int
     *
     * @ORM\Column(name="numTel", type="integer")
     * @Assert\NotBlank(
     *     message="Le champ numero de telephone ne doit pas etre vide"
     * )
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     minMessage="Le cin doit avoir 8 chiffres ",
     *     maxMessage="le cin doit avoir 8 chiffres "
     * )
     */
    private $numTel;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer")
     */
    private $etat;

    /**
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param int $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
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
     * @return Recrutement
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
     * @return Recrutement
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
     * @return Recrutement
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
     * @param string $cin
     *
     * @return Recrutement
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
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
     * @return Recrutement
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
     * Set numTel
     *
     * @param integer $numTel
     *
     * @return Recrutement
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
}

