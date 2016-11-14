<?php

namespace Labs\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Labs\BackBundle\Entity\Post;
use Labs\BackBundle\Form\PostType;
use Labs\BackBundle\Form\PostEditType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Blog controller.
 *
 * @Route("/articles")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="post_list")
     */
    public function  getListPostAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsBackBundle:Post')->findAllPost();
        return $this->render('LabsBackBundle:Posts:list_post.html.twig', array(
            'posts' => $posts
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/create", name="post_create")
     * @Method({"GET", "POST"})
     */
    public function CreatePostAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('post_list');
        }
        return $this->render('LabsBackBundle:Posts:create_post.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/edit/{id}", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editPostAction(Post $post ,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsBackBundle:Post')->find($post);
        if(null === $posts){
            throw new NotFoundHttpException("L'element d'id ".$post." n'existe pas");
        }
        $form = $this->createForm(PostEditType::class, $posts);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('post_list');
        }
        return $this->render('LabsBackBundle:Posts:edit_post.html.twig',array(
            'form' => $form->createView(),
            'id'   => $posts->getId()
        ));
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     * @Route("/delete/{id}", name="post_delete")
     * @Method("GET")
     */
    public function deletePostAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsBackBundle:Post')->find($post);
        if( null === $posts)
            throw new NotFoundHttpException('Le poste '.$post.' n\'existe pas');
        else
            $em->remove($posts);
        $em->flush();
        return $this->redirectToRoute('post_list');
    }

}
