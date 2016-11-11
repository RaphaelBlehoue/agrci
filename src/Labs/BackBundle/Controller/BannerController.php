<?php

namespace Labs\BackBundle\Controller;

use Labs\BackBundle\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Labs\BackBundle\Form\BannerType;
use Labs\BackBundle\Form\BannerEditType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BannerController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/banner/add", name="banner_add")
     */
    public function UploadBannerAction(Request $request)
    {
        $banner = new Banner();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($banner);
            $em->flush();
            return $this->redirect($this->generateUrl('banner_list'));
        }
        return $this->render('LabsBackBundle:Banner:create.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("banner/list", name="banner_list")
     */
    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('LabsBackBundle:Banner')->findAll();
        return $this->render('LabsBackBundle:Banner:index.html.twig', array(
            'banners' => $banner
        ));
    }

    /**
     * @param Banner $banner
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("banner/edit/{id}", name="banner_edit",requirements={"id" = "\d+"})
     */
    public  function bannerEditAction(Banner $banner, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // On recupere l'id du market
        $banners = $em->getRepository('LabsBackBundle:Banner')->findOne($banner);
        if(null === $banners){
            throw new NotFoundHttpException("L'element d'id ".$banners." n'existe pas");
        }
        $form = $this->createForm(BannerEditType::class, $banners);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('banner_list');
        }
        return $this->render('LabsBackBundle:Banner:edit.html.twig',array(
            'form' => $form->createView(),
            'id'  => $banners->getId()
        ));
    }


    /**
     * @param Banner $banner
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("banner/delete/{id}", name="banner_delete", requirements={"id" = "\d+"})
     */
    public function BannerdeleteAction(Banner $banner)
    {
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('LabsBackBundle:Banner')->find($banner);
        if( null === $banner)
            throw new NotFoundHttpException('La banner '.$banner.' n\'existe pas');
        else
            $em->remove($banner);
        $em->flush();
        return $this->redirectToRoute('banner_list');
    }
}
