<?php

namespace Labs\BackBundle\Services;

use Doctrine\ORM\EntityManager;
use Labs\BackBundle\Entity\Project;

class Draft
{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return Project|mixed
     */
    public function DraftCreate()
    {
        $draft = $this->em->getRepository('LabsBackBundle:Project')->getDraft();
        if(null === $draft){
            $post = new Project();
            $post->setDraft(0);
            $post->setOnline(0);
            $this->em->persist($post);
            $this->em->flush();
            $draft = $post;
            return $draft;
        }else{
            return $draft;
        }
    }
}