<?php

namespace Labs\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;



/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Labs\BackBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\NotNull(message="Entrez les caractérisques du project")
     * @ORM\Column(name="settings", type="text", length=255)
     */
    protected $settings;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez la description de la rencontre")
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @var
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Labs\BackBundle\Entity\Media", mappedBy="project", cascade={"remove"})
     */
    protected $medias;


    /**
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;


    public function __construct()
    {
        $this->created = new \DateTime('now');
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
     * @return string
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/project';
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
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param string $settings
     *
     * @return Project
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }

}
