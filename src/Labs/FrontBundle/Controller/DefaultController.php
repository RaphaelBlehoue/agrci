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
            'projects' => $projects,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function SlideAction()
    {
        $slider = $this->getSlideContent();
        return $this->render('LabsFrontBundle:includes:slide.html.twig', array(
            'sliders' => $slider
        ));
    }

    /**
     * @Route("/notre_architect", name="architect")
     */
    public function ArchitectAction()
    {
        die('Notre architect');
    }

    /**
     * @Route("/notre_project", name="project")
     */
    public function ProjectAction()
    {
        die('Notre project');
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

    /**
     * @return array|\Labs\BackBundle\Entity\Banner[]
     */
    private function getSlideContent()
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository('LabsBackBundle:Banner')->findAll();
        return $slides;
    }
}
