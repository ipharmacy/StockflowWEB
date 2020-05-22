<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="descriptionc", type="string", length=255)
     */
    private $descriptionc;

    /**
     * @var string
     *
     * @ORM\Column(name="typec", type="string", length=255)
     */
    private $typec;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datec", type="date")
     */
    private $datec;
    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Publication")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPublication", referencedColumnName="id")
     * })
     */

    private $idPublication;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     */
    private $iduser;


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
     * Set descriptionc
     *
     * @param string $descriptionc
     *
     * @return Commentaire
     */
    public function setDescriptionc($descriptionc)
    {
        $this->descriptionc = $descriptionc;

        return $this;
    }

    /**
     * Get descriptionc
     *
     * @return string
     */
    public function getDescriptionc()
    {
        return $this->descriptionc;
    }

    /**
     * Set typec
     *
     * @param string $typec
     *
     * @return Commentaire
     */
    public function setTypec($typec)
    {
        $this->typec = $typec;

        return $this;
    }

    /**
     * Get typec
     *
     * @return string
     */
    public function getTypec()
    {
        return $this->typec;
    }

    /**
     * Set datec
     *
     * @param \DateTime $datec
     *
     * @return Commentaire
     */
    public function setDatec($datec)
    {
        $this->datec = $datec;

        return $this;
    }

    /**
     * Get datec
     *
     * @return \DateTime
     */
    public function getDatec()
    {
        return $this->datec;
    }

    /**
     * Set idPublication
     *
     * @param integer $idPublication
     *
     * @return Commentaire
     */
    public function setIdPublication($idPublication)
    {
        $this->idPublication = $idPublication;

        return $this;
    }

    /**
     * Get idPublication
     *
     * @return int
     */
    public function getIdPublication()
    {
        return $this->idPublication;
    }

    /**
     * Set idser
     *
     * @param integer $idser
     *
     * @return Commentaire
     */
    public function setIduser($idser)
    {
        $this->iduser = $idser;

        return $this;
    }

    /**
     * Get idser
     *
     * @return int
     */
    public function getIduser()
    {
        return $this->iduser;
    }
}

