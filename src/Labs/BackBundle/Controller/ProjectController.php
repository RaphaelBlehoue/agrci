<?php

namespace Labs\BackBundle\Controller;

use Labs\BackBundle\Entity\Media;
use Labs\BackBundle\Entity\Project;
use Labs\BackBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProjectController
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="project_create")
     * @Method({"GET","POST"})
     */
    public function createAction()
    {
        $draft = $this->get('draft_create')->DraftCreate();
        return $this->redirectToRoute('project_edit', ['id' => $draft->getId()]);
    }


    /**
     * @param Request $request
     * @param Project $project
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Project $project, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $em->getRepository('LabsBackBundle:Project')->find($project);
        if( null === $datas)
        {
            throw new NotFoundHttpException('Article introuvable');
        }
        // Upload Medias
        if($request->isXmlHttpRequest()){
            $response = [];
            if($this->uploadMedia($request, $datas)){
                $response = ['results' => 'true'];
            }else{
                $response = ['results' => 'false'];
            }
            return new JsonResponse($response);
        }

        $form = $this->createForm(ProjectType::class, $datas);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $datas->setDraft(1);
            $em->persist($datas);
            $em->flush();
            return $this->redirectToRoute('media_list', ['id' => $datas->getId()]);
        }
        return $this->render('LabsBackBundle:Projects:edit.html.twig', [
            'form'      => $form->createView(),
            'project'   => $datas
        ]);
    }

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/online", name="project_online")
     * @Method("GET")
     */
    public function AddStatusAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Project')->find($project);
        if(null === $entity)
        {
            throw new NotFoundHttpException('Le projet est introuvable', 404);
        }
        if($entity->getOnline() === 1){
            $entity->setOnline(0);
        }else{
            $entity->setOnline(1);
        }
        $em->flush();
        return $this->redirectToRoute('project_index');
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
        return $this->redirectToRoute('project_index', array(), 302);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return bool
     */
    private function uploadMedia(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $media = new Media();
        $projects = $em->getRepository('LabsBackBundle:Project')->find($project);
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('file');
        $fileName = $projects->getSlug().'_'.md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->container->getParameter('gallery_directory'),
            $fileName
        );
        $media->setUrl($fileName);
        $media->setProject($projects);
        $em->persist($media);
        $em->flush($media);
        return true;
    }
    
    
}
