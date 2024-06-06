<?php

namespace App\EventSubscriber;

use App\Events\AjoutConferenceEvent;
use App\Events\ModifConferenceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AjoutConferenceSubscriber implements EventSubscriberInterface
{
    public function onAjoutConferenceEvent(AjoutConferenceEvent $event): void
    {
        dd('listener de AjoutConferenceEvent ');
    }
    public function onModifConferenceEvent(ModifConferenceEvent $event): void
    {
        dd('listener de ModifConferenceEvent');
    }
    public static function getSubscribedEvents(): array
    {
        return [
            AjoutConferenceEvent::class => 'onAjoutConferenceEvent',
            ModifConferenceEvent::class => 'onModifConferenceEvent'
        ];
    }
}
