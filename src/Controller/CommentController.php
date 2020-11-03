<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment_index")
     */
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/comment/new", name="comment_new")
     */
    public function new(Request $request, Trick $trick)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        //$trickId = $trick->getId();

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setCreatedAt(new \DateTime());
            //$comment->setTrick($trickId);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $this->addFlash(
                'notice',
                'Le commentaire a été enregistré.');

            return $this->redirectToRoute('trick_index');
        }
        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/comment/show/{id}", name="comment_show")
     */
    public function show(Comment $comment)
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment
        ]);
    }

    /**
     * @Route("/comment/edit/{id}", name="comment_edit")
     */
    public function edit(Request $request, Comment $comment)
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'notice',
                'Le commentaire a été modifié.');

            return $this->redirectToRoute('trick_index');
        }
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/comment/remove/{id}", name="comment_remove")
     */
    public function remove(Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->addFlash(
            'notice',
            'Le commentaire a été supprimé.');

        return $this->redirectToRoute('trick_index');
    }
}
