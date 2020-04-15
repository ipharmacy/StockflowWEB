<?php

namespace CongeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conge
 *
 * @ORM\Table(name="conge")
 * @ORM\Entity(repositoryClass="CongeBundle\Repository\CongeRepository")
 */
class Conge
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
     *@ORM\ManyToOne(targetEntity="EmployeBundle\Entity\Employe")
     * @ORM\JoinColumn(name="idEmploye",referencedColumnName="id")
     */
    private $idEmploye;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateConge", type="date")
     */
    private $dateConge;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJours", type="integer")
     */
    private $nbJours;

    /**
     * @return bool
     */
    public function isEtat()
    {
        return $this->etat;
    }

    /**
     * @param bool $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="attachement", type="string", length=255)
     */
    private $attachement;
    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="idUtilisateur", type="integer")
     */
    private $idUtilisateur;

    /**
     * @return int
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * @param int $idUtilisateur
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
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
     * Set idEmploye
     *
     * @param integer $idEmploye
     *
     * @return Conge
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
     * Set dateConge
     *
     * @param \DateTime $dateConge
     *
     * @return Conge
     */
    public function setDateConge($dateConge)
    {
        $this->dateConge = $dateConge;

        return $this;
    }

    /**
     * Get dateConge
     *
     * @return \DateTime
     */
    public function getDateConge()
    {
        return $this->dateConge;
    }

    /**
     * Set nbJours
     *
     * @param integer $nbJours
     *
     * @return Conge
     */
    public function setNbJours($nbJours)
    {
        $this->nbJours = $nbJours;

        return $this;
    }

    /**
     * Get nbJours
     *
     * @return int
     */
    public function getNbJours()
    {
        return $this->nbJours;
    }

    /**
     * Set attachement
     *
     * @param string $attachement
     *
     * @return Conge
     */
    public function setAttachement($attachement)
    {
        $this->attachement = $attachement;

        return $this;
    }

    /**
     * Get attachement
     *
     * @return string
     */
    public function getAttachement()
    {
        return $this->attachement;
    }
}

