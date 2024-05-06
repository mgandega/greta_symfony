<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('truncate', [$this, 'truncate'])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('trunc', [$this, 'trunc']),
        ];
    }

    public function truncate($valeur){
        if (strlen($valeur) >= 15){
            return substr($valeur, 0, 15).' ...';
        }else{
            return $valeur;
        }
    }
    public function trunc($valeur){
        if (strlen($valeur) >= 15){
            return substr($valeur, 0, 15).' ...';
        }else{
            return $valeur;
        }
    }
}
