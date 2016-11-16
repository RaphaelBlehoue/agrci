<?php

namespace Labs\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $about = $this->getAboutContent();
        $projects = $this->getProjectContent();
        return $this->render('LabsFrontBundle:Default:index.html.twig', [
            'about' => $about,
            'projects' => $projects
        ]);
    }

    /**
     * Recuperation de About
     * @return mixed
     */
    private function getAboutContent()
    {
        $em = $this->getDoctrine()->getManager();
        $about = $em->getRepository('LabsBackBundle:About')->findPage();
        return $about;
    }

    /**
     * @return mixed
     */
    private function getProjectContent()
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('LabsBackBundle:Project')->findProjectLimit(8);
        return $project;
    }
}
