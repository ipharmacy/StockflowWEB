<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * rating
 *
 * @ORM\Table(name="ratingKh")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\ratingRepository")
 */
class rating
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
     * @ORM\Column(name="idF", type="integer")
     */
    private $idF;

    /**
     * @var int
     *
     * @ORM\Column(name="ip", type="integer")
     */
    private $ip;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt", type="date")
     */
    private $dt;


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
     * Set idF
     *
     * @param integer $idF
     *
     * @return rating
     */
    public function setIdF($idF)
    {
        $this->idF = $idF;

        return $this;
    }

    /**
     * Get idF
     *
     * @return int
     */
    public function getIdF()
    {
        return $this->idF;
    }

    /**
     * Set ip
     *
     * @param integer $ip
     *
     * @return rating
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return int
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     *
     * @return rating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set dt
     *
     * @param \DateTime $dt
     *
     * @return rating
     */
    public function setDt($dt)
    {
        $this->dt = $dt;

        return $this;
    }

    /**
     * Get dt
     *
     * @return \DateTime
     */
    public function getDt()
    {
        return $this->dt;
    }
}

