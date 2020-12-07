<?php

namespace App\Controller;


use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * SecurityController constructor.
     * @param Mailer $mailer
     * @param UserRepository $userRepository
     */
    public function __construct(Mailer $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/inscription", name="security_registration")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, TokenGeneratorInterface $tokenGenerator)
    {
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setToken($tokenGenerator->generateToken());

            $manager->persist($user);
            $manager->flush();

            $this->mailer->sendEmail($user->getEmail(), $user->getToken(), 'Confirmez votre adresse email');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

/*
    /**
     * @Route("/oubli-mot-de-passe", name="forgot_password")
     * @param Request $request
     * @param UserRepository $users
     * @param TokenGeneratorInterface $tokenGenerator
     */
/*
    public function forgotPassword(Request $request, UserRepository $users, TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $user = $users->findOneByEmail($data['email']);

            if ($user)
            {
                $token = $tokenGenerator->generateToken();

                $user->setResetToken($token);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->mailer->sendEmailResetPassword($user->getEmail(), $user->getToken());

                $this->addFlash('success', 'Un email de réinitialisation vous a été envoyé.');
                return $this->redirectToRoute('security_login');
            }
            else
            {
                $this->addFlash('error', 'Cette adresse e-mail est inconnue');
                return $this->redirectToRoute('security_login');
            }

        }

        return $this->render('security/forgot_password.html.twig',['emailForm' => $form->createView()]);
    }


    /**
     * @Route("/reinitialiser-mot-de-passe/{token}", name="reset_password")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
/*
    public function ResetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if ($user === null)
        {
            $this->addFlash('error', 'Token Inconnu');
            return $this->redirectToRoute('security_login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST'))
        {
            $user->setResetToken(null);

            // On chiffre le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Mot de passe mis à jour');

            return $this->redirectToRoute('security_login');
        }
        else
        {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
*/
    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}

    /**
     * @Route("confirmation-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token)
    {
        $user = $this->userRepository->findOneBy([
            "token" => $token
        ]);

        if($user)
        {
            $user->setToken(null);
            $user->setIsVerified(true);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Compte actif !" );
            return $this->redirectToRoute('trick_home');
        }
        else
        {
            $this->addFlash("error", "Ce compte n'existe pas." );
            return $this->redirectToRoute('trick_home');
        }
    }

}
