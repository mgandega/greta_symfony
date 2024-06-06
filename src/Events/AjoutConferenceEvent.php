<?php
namespace App\Events;

class AjoutConferenceEvent{

    public $conference;
    public $user;

    public function __construct($conference,$user){
        $this->conference = $conference;
        $this->user = $user;
    }

    public function infos(){
        return 'hello';
    }

}