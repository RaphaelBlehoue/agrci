<?php

namespace Labs\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Labs\BackBundle\Entity\Service;
use Labs\BackBundle\Form\ServiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ServicesController extends Controller
{
    
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/services/add", name="service_add")
     */
    public function AddAction(Request $request)
    {
        $entity = new Service();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ServiceType::class, $entity);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('service_list'));
        }
        return $this->render('LabsBackBundle:Service:add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/services/list", name="service_list")
     */
    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Service')->findAll();
        return $this->render('LabsBackBundle:Service:index.html.twig', array(
            'entities' => $entity
        ));
    }

    /**
     * @param Service $service
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/service/edit/{id}", name="service_edit",requirements={"id" = "\d+"})
     */
    public  function EditAction(Service $service, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Service')->findOne($service);
        if(null === $entity){
            throw new NotFoundHttpException("L'element d'id ".$entity." n'existe pas");
        }
        $form = $this->createForm(ServiceType::class, $entity);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('service_list');
        }
        return $this->render('LabsBackBundle:Service:edit.html.twig',array(
            'form' => $form->createView(),
            'id' => $entity->getId()
        ));
    }


    /**
     * @param Service $service
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("/service/delete/{id}", name="service_delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Service $service)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Service')->find($service);
        if( null === $entity)
            throw new NotFoundHttpException('L\'element '.$entity.' n\'existe pas');
        else
            $em->remove($entity);
        $em->flush();
        return $this->redirectToRoute('service_list');
    }
}
