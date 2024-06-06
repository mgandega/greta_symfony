<?php

namespace App\EventListener;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use App\Events\ModifConferenceEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ModifConferenceListener
{
    public function __construct(
        public MailerInterface $mailer, 
        public Environment $twig,
        )
    {
    }
    #[AsEventListener(event: ModifConferenceEvent::class)]
    public function onModifConferenceEvent($event): void
    {
    
            // Génération du contenu HTML avec Twig
            $htmlContent = $this->twig->render('contact\mailer.html.twig', [
                'message' => $event->conference->getDescription(),
                'name' => $event->user->getFirstName(),
            ]);
    
            $email = (new Email())
                ->from('admin@monsite.com')
                ->to($event->user->getEmail())
                ->subject('Time for Symfony Mailer!')
                ->text($event->conference->getDescription())
                ->html($htmlContent);
    
            $this->mailer->send($email);
        }
    }
