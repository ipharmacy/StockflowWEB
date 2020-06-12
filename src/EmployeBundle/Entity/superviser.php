<?php

namespace EmployeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * superviser
 *
 * @ORM\Table(name="superviser")
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\superviserRepository")
 */
class superviser
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
     * @ORM\ManyToOne(targetEntity="TacheBundle\Entity\Tache")
     * @ORM\JoinColumn(name="idTache",referencedColumnName="id")
     */
    private $idTache;

    /**
     * @ORM\ManyToOne(targetEntity="CongeBundle\Entity\Conge")
     * @ORM\JoinColumn(name="idConge",referencedColumnName="id")
     */
    private $idConge;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;


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
     * @return superviser
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
     * Set idTache
     *
     * @param integer $idTache
     *
     * @return superviser
     */
    public function setIdTache($idTache)
    {
        $this->idTache = $idTache;

        return $this;
    }

    /**
     * Get idTache
     *
     * @return int
     */
    public function getIdTache()
    {
        return $this->idTache;
    }

    /**
     * Set idConge
     *
     * @param integer $idConge
     *
     * @return superviser
     */
    public function setIdConge($idConge)
    {
        $this->idConge = $idConge;

        return $this;
    }

    /**
     * Get idConge
     *
     * @return int
     */
    public function getIdConge()
    {
        return $this->idConge;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return superviser
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return superviser
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}

