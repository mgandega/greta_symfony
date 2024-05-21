<?php

namespace App;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class SendMailers
{
    public function __construct(
        public MailerInterface $mailer, 
        public Environment $twig,
        )
    {
    }

    public function sendMail($pseudo,$email,$message)
    {

        // Génération du contenu HTML avec Twig
        $htmlContent = $this->twig->render('contact\mailer.html.twig', [
            'message' => $message,
            'name' => $pseudo,
        ]);

        $email = (new Email())
            ->from($email)
            ->to('admin@monsite.com')
            ->subject('Time for Symfony Mailer!')
            ->text($message)
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}
