<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class SendMailers extends AbstractController
{
    public function __construct(
        public MailerInterface $mailer, 
        public Environment $twig,
        // public ContainerInterface $container
        )
    {
    }

    public function sendMail()
    {
        $message = 'Sending emails is fun again!';
        $name = 'John Doe';

        // Génération du contenu HTML avec Twig
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text($message)
            ->html('hello');

       $this->mailer->send($email);
    }
}
