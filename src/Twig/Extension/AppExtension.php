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
            new TwigFilter('truncate', [$this, 'tronque']),
            new TwigFilter('unique', [$this, 'uniqueFilter']),
            new TwigFilter('montant', [$this, 'montant']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('trunc', [$this, 'trunc']),
        ];
    }

    public function tronque($valeur){
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
    public function uniqueFilter(array $array): array {
        return array_unique($array);
        }

        public function montant(){
            $total = 0;
            for ($i=0; $i< count($_SESSION['panier']['conferenceId']); $i++){
                $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
            }
            return round($total, 2);
        }
}
