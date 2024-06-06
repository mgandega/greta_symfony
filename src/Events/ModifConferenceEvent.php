<?php 
namespace App\Events;

class ModifConferenceEvent{

    public function __construct(public $conference, public $user){}

    public function infos(){
        return 'je suis l\'evenement "ModifConferenceEvent" ';
    }

}