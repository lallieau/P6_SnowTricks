<?php

namespace App\Controller\Security;

use App\Service\Mailer;
use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/snowtricks/mon-compte", name="profile")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     */
    public function edit(Request $request)
    {
        $user= $this->getUser();

        return $this->render('security/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param Mailer $mailer
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @Route("/snowtricks/mon-compte/modifier-mot-de-passe", name="update_password")
     */
    public function updatePassword(Request $request, Mailer $mailer, UserRepository $userRepository, EntityManagerInterface $manager, TokenGeneratorInterface $tokenGenerator) : Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $user = $userRepository->findOneByEmail($data['email']);

            if (!$user) {
                $this->addFlash('error', 'Cette adresse e-mail est inconnue');
                return $this->redirectToRoute('security_login');
            }

            $user->setResetToken($tokenGenerator->generateToken());

            $manager->persist($user);
            $manager->flush();

            $mailer->sendEmail($user->getEmail(), $user->getResetToken(), 'Modifiez votre mot de passe', 'email/update_password_email.html.twig');

            $this->addFlash('success', 'Un email de réinitialisation vous a été envoyé.');
            return $this->redirectToRoute('profile');
        }

        return $this->render('security/update_password.html.twig',['emailForm' => $form->createView()]);

    }

}