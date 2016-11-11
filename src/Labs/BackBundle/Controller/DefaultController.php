<?php

namespace Labs\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Labs\BackBundle\Entity\About;
use Labs\BackBundle\Form\AboutType;
use Labs\BackBundle\Form\AboutEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('LabsBackBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/about/add", name="about_add")
     */
    public function AboutAddAction(Request $request)
    {
        $about = new About();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AboutType::class, $about);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($about);
            $em->flush();
            return $this->redirect($this->generateUrl('about_list'));
        }
        return $this->render('LabsBackBundle:Pages:about_add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/about/list", name="about_list")
     */
    public function AboutlistAction(){
        $em = $this->getDoctrine()->getManager();
        $about = $em->getRepository('LabsBackBundle:About')->findAll();
        return $this->render('LabsBackBundle:Pages:about_index.html.twig', array(
            'abouts' => $about
        ));
    }

    /**
     * @param About $about
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/about/edit/{id}", name="about_edit",requirements={"id" = "\d+"})
     */
    public  function AboutEditAction(About $about, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $abouts = $em->getRepository('LabsBackBundle:About')->findOne($about);
        if(null === $abouts){
            throw new NotFoundHttpException("L'element d'id ".$abouts." n'existe pas");
        }
        $form = $this->createForm(AboutEditType::class, $abouts);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('about_list');
        }
        //dump($abouts); die();
        return $this->render('LabsBackBundle:Pages:about_edit.html.twig',array(
            'form' => $form->createView(),
            'id' => $abouts->getId()
        ));
    }


    /**
     * @param About $about
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("/about/delete/{id}", name="about_delete", requirements={"id" = "\d+"})
     */
    public function AboutdeleteAction(About $about)
    {
        $em = $this->getDoctrine()->getManager();
        $abouts = $em->getRepository('LabsBackBundle:About')->find($about);
        if( null === $abouts)
            throw new NotFoundHttpException('La pages '.$abouts.' n\'existe pas');
        else
            $em->remove($abouts);
        $em->flush();
        return $this->redirectToRoute('about_list');
    }
}
