<?php

namespace Labs\BackBundle\Controller;

use Labs\BackBundle\Entity\Media;
use Labs\BackBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MediaController
 * @package Labs\BackBundle\Controller
 * @Route("/Media/gallery")
 */
class MediaController extends Controller
{
    /**
     * @Route("/", name="media_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $medias = $em->getRepository('LabsBackBundle:Media')->findAll();
        return $this->render('LabsBackBundle:Medias:index.html.twig', array(
            'medias' => $medias
        ));
    }
    
    /**
     * @param Request $request
     * @param Project $project
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/add/{project}/upload", name="media_create")
     */
    public function AddAction(Request $request, Project $project)
    {

        $em = $this->getDoctrine()->getManager();
        $media = new Media();
        $projects = $em->getRepository('LabsBackBundle:Project')->getOne($project);

        if($request->isXmlHttpRequest()){
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $request->files->get('file');
            $fileName = $project->getSlug().'_'.md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->container->getParameter('gallery_directory'),
                $fileName
            );
            $media->setUrl($fileName);
            $media->setProject($projects);
            $em->persist($media);
            $em->flush($media);
            $response = ['success' => 'true'];
            return new JsonResponse($response);
        }

        return $this->render(
            'LabsBackBundle:Medias:upload_project.html.twig', array(
            'projects' => $projects
        ));
    }
    

    /**
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete_project/{project}", name="media_delete_project")
     * @Method("GET")
     */
    public function deleteMediaProjectAction(Media $media, $project)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('LabsBackBundle:Project')->getOne($project);
        if(null === $media)
            throw new NotFoundHttpException('Page Introuvable',null, 404);
        else
            $em->remove($media);
        $em->flush();
        $this->addFlash('success', 'La suppression a été fait avec succès');
        return $this->redirectToRoute('dossier_view', ['id' => $projects->getId()], 302);
    }
}
