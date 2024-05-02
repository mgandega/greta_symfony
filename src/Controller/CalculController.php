<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CalculController extends AbstractController
{
    #[Route('/somme/{arg1}/{arg2}', name: 'somme', defaults: ['arg2'=>20], requirements:['arg2'=>'\d+'])]
    public function somme($arg1,$arg2): Response
    {
        return new Response("le résultat est : ".($arg1 + $arg2));
    }

    #[Route('/article/{page}', name:'article.page', defaults:['page'=>'\d+'])]
    public function article(Request $request, $page):Response{
    // dd($_SERVER);
        // dd($request->attributes->get('_route'));
        // return new Response("le numéro gagnant est : ".$page);
        $personnes = [
            ['nom'=>'macron','prenom'=>'jean'],
            ['nom'=>'durand','prenom'=>'mathieu'],
            ['nom'=>'depardieu','prenom'=>"jerôme"]
        ];

        return $this->render("article/article.html.twig",['page'=>$page,'pers'=>$personnes]);
    }
}
