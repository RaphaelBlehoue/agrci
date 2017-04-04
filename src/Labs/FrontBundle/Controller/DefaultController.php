<?php

namespace Labs\FrontBundle\Controller;

use Labs\BackBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="front_home")
     */
    public function indexAction()
    {
        $about = $this->getAboutContent();
        $projects = $this->getProjectContent();
        $services = $this->getServiceContent();
        $partners = $this->getPartnersContent();
        return $this->render('LabsFrontBundle:Default:index.html.twig', [
            'about' => $about,
            'projects' => $projects,
            'services' => $services,
            'partners' => $partners
        ]);
    }

    /**
     * @Route("/about-us", name="about")
     */
    public function AboutAction()
    {
        $about = $this->getAboutContent();
        $services = $this->getServiceContent();
        $partners = $this->getPartnersContent();
        return $this->render('LabsFrontBundle:Default:about.html.twig', [
            'about' => $about,
            'services' => $services,
            'partners' => $partners
        ]);
    }
    /**
     * @Route("/nos-services", name="services")
     */
    public function ServiceAction()
    {
        $services = $this->getServiceContent();
        $partners = $this->getPartnersContent();
        return $this->render('LabsFrontBundle:Default:service.html.twig',[
            'services' => $services,
            'partners' => $partners
        ]);
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
        $plan = $this->getPlanContent();
        return $this->render('LabsFrontBundle:Default:architect.html.twig',[
            'plans' => $plan
        ]);
    }

    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/notre_project/page-{page}", name="project", requirements={"id" = "\d+"}, defaults={"page" = 1})
     */
    public function ProjectAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $findproject = $em->getRepository('LabsBackBundle:Project')->findAllProjectWithMediasActived();
        $projects  = $this->get('knp_paginator')->paginate(
            $findproject,
            $request->query->getInt('page', $page), 6);
         return $this->render('LabsFrontBundle:Default:project.html.twig',['projects' => $projects]);
    }

    /**
     * @param Project $project
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/{id}/view/{slug}", name="project_view")
     * @Method("GET")
     */
    public function viewProjectAction(Project $project, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('LabsBackBundle:Project')->findOneBy(array(
            'id' => $project,
            'slug' => $slug
        ));
        return $this->render('LabsFrontBundle:Default:project_view.html.twig',['project' => $project]);
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
        $partners = $this->getPartnersContent();
        return $this->render('LabsFrontBundle:Default:partners.html.twig',['partners' => $partners]);
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
     * Recuperation de Plan
     * @return mixed
     */
    private function getPlanContent()
    {
        $em = $this->getDoctrine()->getManager();
        $plan = $em->getRepository('LabsBackBundle:Plan')->findLimit(5);
        return $plan;
    }

    /**
     * Recuperation de Service
     * @return mixed
     */
    private function getServiceContent()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('LabsBackBundle:Service')->findAll();
        return $entities;
    }

    /**
     * Recuperation de partners
     * @return mixed
     */
    private function getPartnersContent()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('LabsBackBundle:Partner')->findAll();
        return $entities;
    }

    /**
     * @return array
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
