<?php

namespace EntrepotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile ;

use Symfony\Component\Validator\Constraints as Assert ;

/**
 * Entrepot
 *
 * @ORM\Table(name="entrepot")
 * @ORM\Entity(repositoryClass="EntrepotBundle\Repository\EntrepotRepository")
 */
class Entrepot
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
     * @ORM\Column(name="longitude", type="integer")
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", nullable=true)
     */
    private $rating;

    /**
     * @var int
     *
     * @ORM\Column(name="vues", type="integer")
     */
    private $vues;

    /**
     * @var int
     *
     * @ORM\Column(name="largitude", type="integer", nullable=true)
     */
    private $largitude;


    /**
     * @var int
     *
     * @ORM\Column(name="nb_rates", type="integer", nullable=true)
     */
    private $nb_rates;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="idUtilisateur", type="integer")
     */
    private $idUtilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;


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
    public function getNbRates()
    {
        return $this->nb_rates;
    }


    /**
     * @return int
     */
    public function getNb_rates()
    {
        return $this->nb_rates;
    }



    /**
     * @Assert\File(maxSize="500k") ;
     */
    public $file ;



    /**
     * Set longitude
     *
     * @param integer $longitude
     *
     * @return Entrepot
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return int
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return Entrepot
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }
    /**
     * Set nb_rates
     *
     * @param integer $nb_rates
     *
     * @return Entrepot
     */
    public function setNb_rates($nb_rates)
    {
        $this->nb_rates = $nb_rates;

        return $this;
    }



    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set vues
     *
     * @param integer $vues
     *
     * @return Entrepot
     */
    public function setVues($vues)
    {
        $this->vues = $vues;

        return $this;
    }

    /**
     * Get vues
     *
     * @return int
     */
    public function getVues()
    {
        return $this->vues;
    }

    /**
     * Set largitude
     *
     * @param integer $largitude
     *
     * @return Entrepot
     */
    public function setLargitude($largitude)
    {
        $this->largitude = $largitude;

        return $this;
    }

    /**
     * Get largitude
     *
     * @return int
     */
    public function getLargitude()
    {
        return $this->largitude;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Entrepot
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Entrepot
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
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
     * @return Entrepot
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Entrepot
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
     * Set image
     *
     * @param string $image
     *
     * @return Entrepot
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }


    protected  function getUploadRootDir()
    {
        return dirname(__FILE__).'../../../../web/'.$this->getUploadDir().'/'.$this->image ;
    }
    protected function  getUploadDir()
    {
        return 'uploads/images' ;
    }
    public function uploadProfilePicture()
    {
        //beddel fonction 9dima hekka
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }
        if(!$this->id){
            $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        }else{

            $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        }
        $this->
        image=$this->file->getClientOriginalName();
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->image ;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file=$file ;
    }
public function setRating2($rating)
{
    if ($this->nb_rates==1)
    {
        $this->rating=$rating ;
    }else {
        $this->rating = ($this->rating + $rating) / 2;
    }
    return $this ;
}



}

