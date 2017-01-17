<?php

namespace Labs\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="front_home")
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
     * @Route("/about-us", name="about")
     */
    public function AboutAction()
    {
        $about = $this->getAboutContent();
        return $this->render('LabsFrontBundle:Default:about.html.twig', [
            'about' => $about
        ]);
    }
    /**
     * @Route("/nos-services", name="services")
     */
    public function ServiceAction()
    {
        die('lol');
    }

    /**
     * @Route("/nos-plans", name="plans")
     */
    public function PlanAction()
    {
        return $this->render('LabsFrontBundle:Default:plan.html.twig');
    }
    
    /**
     * @Route("/notre_architect", name="architect")
     */
    public function ArchitectAction()
    {
        return $this->render('LabsFrontBundle:Default:architect.html.twig');
    }

    /**
     * @Route("/notre_project", name="project")
     */
    public function ProjectAction()
    {
        return $this->render('LabsFrontBundle:Default:project.html.twig');
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function BlogAction()
    {
        return $this->render('LabsFrontBundle:Default:blog.html.twig');
    }

    /**
     * @Route("/contact-us", name="contact")
     */
    public function ContactAction()
    {
        return $this->render('LabsFrontBundle:Default:contact.html.twig');
    }

    /**
     * @Route("/partners", name="partners")
     */
    public function PartnersAction()
    {
        return $this->render('LabsFrontBundle:Default:partners.html.twig');
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
