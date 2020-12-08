<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_home")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $trick = $repository->findAll();

        return $this->render('trick/index.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("snowtricks/ajouter-une-figure", name="trick_new")
     */
    public function new(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $trick->setUser($this->getUser());
            $trick->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'success',
                'La figure a été enregistrée avec succès.');

            return $this->redirectToRoute('trick_home');
        }
        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/snowtricks/figure/{title}", name="trick_show")
     */
    public function show(Trick $trick, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setCreatedAt(new \DateTime());
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                'Le commentaire a été enregistré avec succès.');

            return $this->redirectToRoute('trick_show', [
                'title' => $trick->getTitle(),
            ]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/snowtricks/modifier-la-figure/{title}", name="trick_edit")
     */
    public function edit(Request $request, Trick $trick)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $trick->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'success',
                'La figure a été modifiée avec succès.');

            return $this->redirectToRoute('trick_home');
        }
        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/snowtrick/supprimer-la-figure/{id}", name="trick_remove")
     */
    public function remove(Trick $trick)
    {
        foreach ($trick->getComments() as $comment)
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();

        $this->addFlash(
            'success',
            'La figure a été supprimée définitivement.');

        return $this->redirectToRoute('trick_home');
    }
}
