<?php

namespace Labs\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Labs\BackBundle\Entity\Team;
use Labs\BackBundle\Form\TeamType;
use Labs\BackBundle\Form\TeamEditType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Product controller.
 *
 * @Route("/Team")
 */
class TeamController extends Controller
{
    /**
     * @Route("/", name="team_list")
     */
    public function  getListTeamAction()
    {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('LabsBackBundle:Team')->findAll();
        return $this->render('LabsBackBundle:Team:list_team.html.twig', array(
            'teams' => $teams
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/create", name="team_create")
     * @Method({"GET", "POST"})
     */
    public function CreateTeamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('team_list');
        }
        return $this->render('LabsBackBundle:Team:create_team.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Team $team
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/edit/{id}", name="team_edit")
     * @Method({"GET", "POST"})
     */
    public function editTeamAction(Team $team ,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // On recupere l'id du market
        $teams = $em->getRepository('LabsBackBundle:Team')->findOne($team);
        if(null === $teams){
            throw new NotFoundHttpException("L'element d'id ".$teams." n'existe pas");
        }
        $form = $this->createForm(TeamEditType::class, $teams);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('team_list');
        }
        return $this->render('LabsBackBundle:Team:edit_team.html.twig',array(
            'form' => $form->createView(),
            'id'   => $teams->getId()
        ));
    }

    /**
     * @param Team $team
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("/delete/{id}", name="team_delete")
     * @Method("GET")
     */
    public function deleteTeamAction(Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('LabsBackBundle:Team')->find($team);
        if( null === $teams)
            throw new NotFoundHttpException('equipe '.$teams.' n\'existe pas');
        else
            $em->remove($teams);
        $em->flush();
        return $this->redirectToRoute('team_list');
    }

}
