<?php

namespace Labs\BackBundle\Controller;

use Labs\BackBundle\Entity\Media;
use Labs\BackBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/list", name="media_list")
     * @Method("GET")
     * @Template()
     * @ParamConverter("project", class="LabsBackBundle:Project")
     */
    public function ChoiceMediaInFrontAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $em->getRepository('LabsBackBundle:Project')->find($project);
        if(!$datas)
        {
            throw new NotFoundHttpException('l\'article ou les medias n\'existe pas');
        }
        $medias = $em->getRepository('LabsBackBundle:Media')->findForPostMedia($project);
        return $this->render('LabsBackBundle:Medias:list.html.twig', [
            'article' => $project,
            'medias' => $medias
        ]);
    }

    /**
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/in/front/{id}", name="add_media_front")
     * @Method("GET")
     */
    public function AddMediaInFrontAction(Media $media)
    {
        $em = $this->getDoctrine()->getManager();
        $medias = $em->getRepository('LabsBackBundle:Media')->findOneMedia($media);
        if(!$media){
            throw $this->createNotFoundException('Le media photo ou image n\'existe pas');
        }
        //Rechercher tous les medias qui ont la même clé etrangère sauf celle de l'id
        $oldMedia = $em->getRepository('LabsBackBundle:Media')->findMediaIsNotMedia($medias->getId(), $medias->getProject());
        //Mettre la valeur de toute les valeurs trouvée a active = 0
        foreach ($oldMedia as $m){
            $m->setActived(0);
        }
        //Ensuite mettre le medias trouve en question a active = 1
        $medias->setActived(1);
        $em->flush();
        return $this->redirectToRoute('project_index');
    }
}
