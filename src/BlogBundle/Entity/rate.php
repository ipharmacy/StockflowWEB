<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * rate
 *
 * @ORM\Table(name="rate")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\rateRepository")
 */
class rate
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
     * @var int
     *
     * @ORM\Column(name="idb", type="integer")
     */
    private $idb;

    /**
     * @var int
     *
     * @ORM\Column(name="likeb", type="integer")
     */
    private $likeb;

    /**
     * @var int
     *
     * @ORM\Column(name="dislikeb", type="integer")
     */
    private $dislikeb;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;


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
     * Set idb
     *
     * @param integer $idb
     *
     * @return rate
     */
    public function setIdb($idb)
    {
        $this->idb = $idb;

        return $this;
    }

    /**
     * Get idb
     *
     * @return int
     */
    public function getIdb()
    {
        return $this->idb;
    }

    /**
     * Set likeb
     *
     * @param integer $likeb
     *
     * @return rate
     */
    public function setLikeb($likeb)
    {
        $this->likeb = $likeb;

        return $this;
    }

    /**
     * Get likeb
     *
     * @return int
     */
    public function getLikeb()
    {
        return $this->likeb;
    }

    /**
     * Set dislikeb
     *
     * @param integer $dislikeb
     *
     * @return rate
     */
    public function setDislikeb($dislikeb)
    {
        $this->dislikeb = $dislikeb;

        return $this;
    }

    /**
     * Get dislikeb
     *
     * @return int
     */
    public function getDislikeb()
    {
        return $this->dislikeb;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return rate
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}

