<?php

namespace Labs\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Labs\BackBundle\Entity\Plan;
use Labs\BackBundle\Form\PlanType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PlansController extends Controller
{
    
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/plans/add", name="plan_add")
     */
    public function AddAction(Request $request)
    {
        $entity = new Plan();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PlanType::class, $entity);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('plan_list'));
        }
        return $this->render('LabsBackBundle:Plan:add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/plans/list", name="plan_list")
     */
    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Plan')->findAll();
        return $this->render('LabsBackBundle:Plan:index.html.twig', array(
            'entities' => $entity
        ));
    }

    /**
     * @param Plan $plan
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/plan/edit/{id}", name="plan_edit",requirements={"id" = "\d+"})
     */
    public  function EditAction(Plan $plan, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Plan')->findOne($plan);
        if(null === $entity){
            throw new NotFoundHttpException("L'element d'id ".$entity." n'existe pas");
        }
        $form = $this->createForm(PlanType::class, $entity);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('plan_list');
        }
        return $this->render('LabsBackBundle:Plan:edit.html.twig',array(
            'form' => $form->createView(),
            'id' => $entity->getId()
        ));
    }


    /**
     * @param Plan $plan
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("/plan/delete/{id}", name="plan_delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Plan $plan)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsBackBundle:Plan')->find($plan);
        if( null === $entity)
            throw new NotFoundHttpException('L\'element '.$entity.' n\'existe pas');
        else
            $em->remove($entity);
        $em->flush();
        return $this->redirectToRoute('plan_list');
    }
}
