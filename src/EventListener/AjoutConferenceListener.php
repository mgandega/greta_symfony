<?php

namespace App\EventListener;

use App\Events\AjoutConferenceEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AjoutConferenceListener
{
    #[AsEventListener(event: AjoutConferenceEvent::class)]
    public function onAjoutConferenceEvent(AjoutConferenceEvent $event): void
    {
        dd('je suis un écouteur de cette évênement');
    }
}
