<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;


    /**
     * Mailer constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $token, $subject, $path)
    {
        $email = (new TemplatedEmail())
            ->from('test@gmail.com')
            ->to(new Address($email))
            ->subject($subject)

            ->htmlTemplate($path)

            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'token' => $token,
            ]);
        $this->mailer->send($email);
    }

/*
    public function sendEmailResetPassword($email, $token)
    {
        $email = (new TemplatedEmail())
            ->from('test@gmail.com')
            ->to(new Address($email))
            ->subject('Réinitialisation mot de passe')

            ->htmlTemplate('email/reset_password_email.html.twig')

            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'token' => $token,
            ]);

        $this->mailer->send($email);
    }
*/
}