<?php

namespace App\Controller\Security;


use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
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
     * @Route("snowtricks/connexion/oubli-mot-de-passe", name="forgot_password")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserRepository $users
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
        public function forgotPassword(Request $request, EntityManagerInterface $manager, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator): Response
        {
            $form = $this->createForm(ForgotPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $data = $form->getData();
                $user = $userRepository->findOneByEmail($data['email']);

                if (!$user)
                {
                    $this->addFlash('error', 'Cette adresse e-mail est inconnue');
                    return $this->redirectToRoute('security_login');
                }
                    $user->setResetToken($tokenGenerator->generateToken());

                    $manager->persist($user);
                    $manager->flush();

                    $this->mailer->sendEmail($user->getEmail(), $user->getResetToken(), 'Réinitialisez votre mot de passe', 'email/reset_password_email.html.twig');

                    $this->addFlash('success', 'Un email de réinitialisation vous a été envoyé.');
                    return $this->redirectToRoute('security_login');
                }

            return $this->render('security/forgot_password.html.twig',['emailForm' => $form->createView()]);
        }


    /**
     * @Route("/snowtricks/reinitialiser-mot-de-passe/{token}", name="reset_password")
     * @param Request $request
     * @param string $token
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
        public function ResetPassword(Request $request, string $token, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
        {
            //$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
            $user = $this->userRepository->findOneBy([
                "reset_token" => $token
            ]);

            if ($user === null)
            {
                $this->addFlash('error', 'Token Inconnu');
                return $this->redirectToRoute('security_login');
            }

            $form = $this->createForm(ResetPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $hash = $encoder->encodePassword($user, $user->getPassword());

                $user->setPassword($hash);
                $user->setResetToken(null);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a été mis à jour.');

                return $this->redirectToRoute('security_login');
            }
            return $this->render('security/reset_password.html.twig', [
                'form' => $form->createView()
            ]);
        }
}
