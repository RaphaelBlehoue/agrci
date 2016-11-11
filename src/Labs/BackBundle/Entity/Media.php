<?php

namespace Labs\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="Labs\BackBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Media
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    protected $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="actived", type="boolean")
     */
    protected $actived;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Labs\BackBundle\Entity\Project", inversedBy="medias")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $project;

    
    public function __construct()
    {
        $this->actived = false;
        $this->status = true;
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
     * Set url
     *
     * @param string $url
     *
     * @return Media
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Media
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set actived
     *
     * @param boolean $actived
     *
     * @return Media
     */
    public function setActived($actived)
    {
        $this->actived = $actived;

        return $this;
    }

    /**
     * Get actived
     *
     * @return bool
     */
    public function getActived()
    {
        return $this->actived;
    }

    /**
     * Set project
     *
     * @param \Labs\BackBundle\Entity\Project $project
     *
     * @return Media
     */
    public function setProject(\Labs\BackBundle\Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
 * Get project
 *
 * @return \Labs\BackBundle\Entity\Project
 */
    public function getProject()
    {
        return $this->project;
    }


    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/gallery';
    }


    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }


    /**
     * @return string
     */
    public function getAssertPath()
    {
        return $this->getUploadDir().'/'.$this->url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function deleteMedia()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->getAssertPath())) {
            // On supprime le fichier
            unlink($this->getAssertPath());
        }
    }
}
