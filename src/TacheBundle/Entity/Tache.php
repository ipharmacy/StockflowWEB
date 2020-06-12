<?php

namespace TacheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tache
 *
 * @ORM\Table(name="tache")
 * @ORM\Entity(repositoryClass="TacheBundle\Repository\TacheRepository")
 */
class Tache
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
     * @ORM\ManyToOne(targetEntity="EmployeBundle\Entity\Employe")
     * @ORM\JoinColumn(name="idEmploye",referencedColumnName="id")
     */
    private $idEmploye;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le champ commentaire ne doit pas etre vide"
     * )
     */
    private $commentaire;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="idUtilisateur",referencedColumnName="id")
     */
    private $idUtilisateur;



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
     * Set idEmploye
     *
     * @param integer $idEmploye
     *
     * @return Tache
     */
    public function setIdEmploye($idEmploye)
    {
        $this->idEmploye = $idEmploye;

        return $this;
    }

    /**
     * Get idEmploye
     *
     * @return int
     */
    public function getIdEmploye()
    {
        return $this->idEmploye;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Tache
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Tache
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return bool
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     *
     * @return Tache
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
     * @var \DateTime
     *
     * @ORM\Column(name="DateAttribution", type="datetime")
     *
     */
    private $DateAttribution;

    /**
     * @return \DateTime
     */
    public function getDateLimit()
    {
        return $this->DateLimit;
    }

    /**
     * @param \DateTime $DateLimit
     */
    public function setDateLimit($DateLimit)
    {
        $this->DateLimit = $DateLimit;
    }

    /**
     *
     * @Assert\NotBlank(
     *     message="Le champ date limite ne doit pas etre vide"
     * )
     * @Assert\GreaterThan(
     *     value="today",
     *     message="La date limite doit etre superieur a la date courante"
     * )
     * @var \DateTime
     *
     * @ORM\Column(name="DateLimit", type="datetime")
     *
     */
    private $DateLimit;

    /**
     * @return \DateTime
     */
    public function getDateAttribution()
    {
        return $this->DateAttribution;
    }

    /**
     * @param \DateTime $DateAttribution
     */
    public function setDateAttribution($DateAttribution)
    {
        $this->DateAttribution = $DateAttribution;
    }





}

