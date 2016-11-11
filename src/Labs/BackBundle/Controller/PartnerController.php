<?php

namespace Labs\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Labs\BackBundle\Entity\Partner;
use Labs\BackBundle\Form\PartnerType;
use Labs\BackBundle\Form\PartnerEditType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Partner controller.
 *
 * @Route("/partner")
 */
class PartnerController extends Controller
{
    /**
     * @Route("/", name="partner_list")
     */
    public function  getListPartnerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $partners = $em->getRepository('LabsBackBundle:Partner')->findAll();
        return $this->render('LabsBackBundle:Partner:list_partner.html.twig', array(
            'partners' => $partners
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/create", name="partner_create")
     * @Method({"GET", "POST"})
     */
    public function CreatePartnerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($partner);
            $em->flush();
            return $this->redirectToRoute('partner_list');
        }
        return $this->render('LabsBackBundle:Partner:create_partner.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Partner $partner
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/edit/{id}", name="partner_edit")
     * @Method({"GET", "POST"})
     */
    public function editPartnerAction(Partner $partner ,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // On recupere l'id du market
        $partners = $em->getRepository('LabsBackBundle:Partner')->findOne($partner);
        if(null === $partners){
            throw new NotFoundHttpException("L'element d'id ".$partners." n'existe pas");
        }
        $form = $this->createForm(PartnerEditType::class, $partners);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('partner_list');
        }
        return $this->render('LabsBackBundle:Partner:edit_partner.html.twig',array(
            'form' => $form->createView(),
            'id'   => $partners->getId()
        ));
    }

    /**
     * @param Partner $partner
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("/delete/{id}", name="partner_delete")
     * @Method("GET")
     */
    public function deletePartnerAction(Partner $partner)
    {
        $em = $this->getDoctrine()->getManager();
        $partners = $em->getRepository('LabsBackBundle:Partner')->find($partner);
        if( null === $partners)
            throw new NotFoundHttpException('Le partenaire '.$partners.' n\'existe pas');
        else
            $em->remove($partners);
        $em->flush();
        return $this->redirectToRoute('partner_list');
    }

}
