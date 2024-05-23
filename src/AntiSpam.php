<?php

namespace App;

class AntiSpam
{

    public function alert($mess)
    {
        return strlen($mess) <= 10;
    }
}
