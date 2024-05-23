<?php

namespace App;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class RegisterMail
{

    public $mailer;
    public $twig;
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send($firstname, $lastname, $email): Response
    {

        $message = $this->twig->render("registration/registerMail.html.twig", [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email
        ]);
        $email = (new Email())
            ->from('admin@gmail.com')
            ->to($email)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html($message);

        $this->mailer->send($email);
        return new Response('ok');
    }
}
