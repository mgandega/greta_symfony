<?php

namespace App\EventSubscriber;

use Date;
use DateTime;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use App\Events\SuppConferenceEvent;
use App\Events\AjoutConferenceEvent;
use App\Events\ModifConferenceEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AjoutConferenceSubscriber implements EventSubscriberInterface
{
    public function __construct(
        public MailerInterface $mailer,
        public Environment $twig,
    ) {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            AjoutConferenceEvent::class => 'onAjoutConferenceEvent',
            ModifConferenceEvent::class => 'onModifConferenceEvent',
            SuppConferenceEvent::class => 'onSuppConferenceEvent'
        ];
    }

    public function onAjoutConferenceEvent(AjoutConferenceEvent $event): void
    {
        dd('listener de AjoutConferenceEvent ');
    }

    public function onModifConferenceEvent(ModifConferenceEvent $event): void
    {
        dd('listener de ModifConferenceEvent');
    }

    public function onSuppConferenceEvent(SuppConferenceEvent $event): void
    {
        $message = $event->conference->getDescription();
        $titre = $event->conference->getTitre();
        $pseudo = $event->user->getFirstname();
        $email = $event->user->getEmail();

        $date = new Datetime($datetime = 'now');
        // Génération du contenu HTML avec Twig
        $htmlContent = $this->twig->render('contact\mailer.html.twig', [
            'message' => $message,
            'name' => $pseudo,
            'titre' => $titre,
            'date' => $date

        ]);

        $email = (new Email())
            ->from('admin@monsite.com')
            ->to($email)
            ->subject('Confirmation de suppression')
            ->text($message)
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}
