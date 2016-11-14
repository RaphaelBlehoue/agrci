<?php

namespace Labs\BackBundle\Controller;

use Labs\BackBundle\Entity\Dossier;
use Labs\BackBundle\Entity\Project;
use Labs\BackBundle\Form\DossierType;
use Labs\BackBundle\Form\DossierEditType;
use Labs\BackBundle\Form\ProjectEditType;
use Labs\BackBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DossierController
 * @package Labs\BackBundle\Controller
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/", name="project_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('LabsBackBundle:Project')->findAll();
        return $this->render('LabsBackBundle:Projects:index.html.twig', array(
            'projects' => $projects
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="project_create")
     */
    public function CreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isValid()){
            $em->persist($project);
            $em->flush();
            $this->addFlash('success', 'L\'enregistrement  a été fait avec succès');
            return $this->redirectToRoute('media_create',['project' => $project->getId()], 302);
        }
        return $this->render('LabsBackBundle:Projects:create.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Project $project, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('LabsBackBundle:Dossier')->find($project);
        if(null === $projects)
        {
            throw new NotFoundHttpException('Page Introuvable',null, 404);
        }
        $form = $this->createForm(ProjectEditType::class, $projects);
        $form->handleRequest($request);

        if($form->isValid()){
            $em->flush();
            $this->addFlash('success', 'La modification a été effectué');
            return $this->redirectToRoute('project_view', array('id' => $projects->getId()), 302);
        }
        return $this->render('LabsBackBundle:Projects:edit.html.twig',array(
            'form' => $form->createView(),
            'id'   => $projects->getId()
        ));
    }

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/view", name="project_view")
     * @Method({"GET", "POST"})
     */
    public function ProjectViewAction( Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('LabsBackBundle:Project')->getOneAndAssociation($project);
        if(null === $projects)
        {
            throw new NotFoundHttpException('Page Introuvable',null, 404);
        }
        return $this->render('LabsBackBundle:Projects:project_view.html.twig',array(
            'projects' => $projects
        ));
    }

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete", name="project_delete")
     * @Method("GET")
     */
    public function deleteAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('LabsBackBundle:Project')->find($project);
        if(null === $projects)
            throw new NotFoundHttpException('Page Introuvable',null, 404);
        else
            $em->remove($projects);
        $em->flush();
        $this->addFlash('success', 'La suppression a été fait avec succès');
        return $this->redirectToRoute('project_index', array(), 302);
    }
    
    
}
