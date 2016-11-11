<?php

namespace Labs\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * Dossier
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Labs\BackBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotNull(message="Entrez le nom du dossier")
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom du marié avant de continuer")
     * @ORM\Column(name="wedding_men", type="string", length=255)
     */
    protected $weddingMen;

    /**
     * @var
     * @Assert\NotBlank(message="Entrez le contenu avant de continuer")
     * @ORM\Column(name="content_wedding_men", type="text")
     */
    protected $content_wedding_men;

    /**
     * @Assert\File(
     *     maxSize="3M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"}
     * )
     *
     * @Vich\UploadableField(mapping="dossier_image_page", fileNameProperty="profile_men")
     *
     * @var File $profileMenFile
     */
    protected $profileMenFile;


    /**
     * @var string
     *
     * @ORM\Column(name="profile_men", type="string", length=225)
     */
    protected $profile_men;


    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom de la marié avant de continuer")
     * @ORM\Column(name="wedding_women", type="string", length=255)
     */
    protected $weddingWomen;


    /**
     * @var
     * @Assert\NotBlank(message="Entrez le contenu avant de continuer")
     * @ORM\Column(name="content_wedding_women", type="text")
     */
    protected $content_wedding_women;

    /**
     * @Assert\File(
     *     maxSize="3M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"}
     * )
     *
     * @Vich\UploadableField(mapping="dossier_image_page", fileNameProperty="profile_women")
     *
     * @var File $profileWomenFile
     */
    protected $profileWomenFile;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_women", type="string", length=225)
     */
    protected $profile_women;



    /**
     * @var string
     * @Assert\NotNull(message="Entrez la description de la rencontre")
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @var \DateTime
     * @Assert\NotNull(message="Rentrez la date de céremonie")
     * @Assert\Date(message="Entrez une date valide")
     * @ORM\Column(name="ceremony_date", type="date")
     */
    protected $ceremonyDate;

    /**
     * @var string
     *
     * @ORM\Column(name="colors", type="string", length=255)
     */
    protected $colors;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    protected $video;


    /**
     * @var
     *
     * @ORM\Column(name="online", type="boolean")
     */
    protected $online;

    /**
     * @var
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Labs\BackBundle\Entity\Media", mappedBy="dossier", cascade={"remove"})
     */
    protected $medias;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Labs\BackBundle\Entity\Best", mappedBy="dossier", cascade={"remove"})
     */
    protected $bests;

    /**
     * @var
     * @Assert\NotBlank(message="Faite le choix d'un pack avant de continuer")
     * @ORM\ManyToOne(targetEntity="Labs\BackBundle\Entity\Packages", inversedBy="dossier")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    protected $package;

    /**
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;


    public function __construct()
    {
        $this->colors = '#fff';
        $this->created = new \DateTime('now');
        $this->online = false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set weddingMen
     *
     * @param string $weddingMen
     *
     * @return Dossier
     */
    public function setWeddingMen($weddingMen)
    {
        $this->weddingMen = $weddingMen;

        return $this;
    }

    /**
     * Get weddingMen
     *
     * @return string
     */
    public function getWeddingMen()
    {
        return $this->weddingMen;
    }

    /**
     * Set weddingWomen
     *
     * @param string $weddingWomen
     *
     * @return Dossier
     */
    public function setWeddingWomen($weddingWomen)
    {
        $this->weddingWomen = $weddingWomen;

        return $this;
    }

    /**
     * Get weddingWomen
     *
     * @return string
     */
    public function getWeddingWomen()
    {
        return $this->weddingWomen;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Dossier
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set ceremonyDate
     *
     * @param \DateTime $ceremonyDate
     *
     * @return Dossier
     */
    public function setCeremonyDate($ceremonyDate)
    {
        $this->ceremonyDate = $ceremonyDate;

        return $this;
    }

    /**
     * Get ceremonyDate
     *
     * @return \DateTime
     */
    public function getCeremonyDate()
    {
        return $this->ceremonyDate;
    }

    /**
     * Set colors
     *
     * @param string $colors
     *
     * @return Dossier
     */
    public function setColors($colors)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get colors
     *
     * @return string
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Get video
     * 
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set video
     * 
     * @param string $video
     * 
     * @return Dossier
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }


    /**
     * Add media
     *
     * @param \Labs\BackBundle\Entity\Media $media
     *
     * @return Dossier
     */
    public function addMedia(\Labs\BackBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \Labs\BackBundle\Entity\Media $media
     */
    public function removeMedia(\Labs\BackBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add best
     *
     * @param \Labs\BackBundle\Entity\Best $best
     *
     * @return Dossier
     */
    public function addBest(\Labs\BackBundle\Entity\Best $best)
    {
        $this->bests[] = $best;

        return $this;
    }

    /**
     * Remove best
     *
     * @param \Labs\BackBundle\Entity\Best $best
     */
    public function removeBest(\Labs\BackBundle\Entity\Best $best)
    {
        $this->bests->removeElement($best);
    }

    /**
     * Get bests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBests()
    {
        return $this->bests;
    }

    /**
     * @return mixed
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param mixed $online
     */
    public function setOnline($online)
    {
        $this->online = $online;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
    

    /**
     * Set package
     *
     * @param \Labs\BackBundle\Entity\Packages $package
     *
     * @return Dossier
     */
    public function setPackage(\Labs\BackBundle\Entity\Packages $package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return \Labs\BackBundle\Entity\Packages
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param File|null $image
     * @return $this
     */
    public function setProfileMenFile(File $image = null)
    {
        $this->profileMenFile = $image;
        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->created = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return File
     */
    public function getProfileMenFile()
    {
        return $this->profileMenFile;
    }


    /**
     * @return string
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/dossier';
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    public function getAssertPathMen()
    {
        return $this->getUploadDir().'/'.$this->profile_men;
    }

    /**
     * @return string
     */
    public function getAssertPathWomen()
    {
        return $this->getUploadDir().'/'.$this->profile_women;
    }


    /**
     * Set contentWeddingMen
     *
     * @param string $contentWeddingMen
     *
     * @return Dossier
     */
    public function setContentWeddingMen($contentWeddingMen)
    {
        $this->content_wedding_men = $contentWeddingMen;

        return $this;
    }

    /**
     * Get contentWeddingMen
     *
     * @return string
     */
    public function getContentWeddingMen()
    {
        return $this->content_wedding_men;
    }

    /**
     * Set profileMen
     *
     * @param string $profileMen
     *
     * @return Dossier
     */
    public function setProfileMen($profileMen)
    {
        $this->profile_men = $profileMen;

        return $this;
    }

    /**
     * Get profileMen
     *
     * @return string
     */
    public function getProfileMen()
    {
        return $this->profile_men;
    }

    /**
     * @param File|null $image
     * @return $this
     */
    public function setProfileWomenFile(File $image = null)
    {
        $this->profileWomenFile = $image;
        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->created = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return File
     */
    public function getProfileWomenFile()
    {
        return $this->profileWomenFile;
    }

    /**
     * Set contentWeddingWomen
     *
     * @param string $contentWeddingWomen
     *
     * @return Dossier
     */
    public function setContentWeddingWomen($contentWeddingWomen)
    {
        $this->content_wedding_women = $contentWeddingWomen;

        return $this;
    }

    /**
     * Get contentWeddingWomen
     *
     * @return string
     */
    public function getContentWeddingWomen()
    {
        return $this->content_wedding_women;
    }

    /**
     * Set profileWomen
     *
     * @param string $profileWomen
     *
     * @return Dossier
     */
    public function setProfileWomen($profileWomen)
    {
        $this->profile_women = $profileWomen;

        return $this;
    }

    /**
     * Get profileWomen
     *
     * @return string
     */
    public function getProfileWomen()
    {
        return $this->profile_women;
    }
}
