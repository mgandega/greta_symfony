<?php

namespace App\EventListener;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use App\Events\AjoutConferenceEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AjoutConferenceListener
{
    public function __construct(
        public MailerInterface $mailer,
        public Environment $twig,
    ) {
    }
    // AjoutConferenceEvent::class => 'App\Events\AjoutConferenceEvent'
    #[AsEventListener(event: AjoutConferenceEvent::class)]
    public function onAjoutConferenceEvent(AjoutConferenceEvent $event): void
    {

        $email = $event->user->getEmail();
        $pseudo = $event->user->getFirstname();
        $message = $event->conference->getDescription();
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
