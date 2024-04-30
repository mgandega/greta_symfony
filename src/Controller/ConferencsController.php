<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferencsController extends AbstractController
{
           public $conferences = [
            [
                'id'=>1,
                'titre'=>'conference Symfony',
                'description' => 'Conférence où on parlera des evênements et des subscribers',
                'lieu' =>'paris',
                "date"=> "24-05-2024"
            ],
            [
                'id'=>2,
                'titre'=>'conference Laravel',
                'description' => 'envoi des notifications à temps reels avec symfony messenger',
                'lieu' =>'lyon',
                "date"=> "18-06-2024"
            ],
            [
                'id'=>3,
                'titre'=>'conference drupal',
                'description' => 'comment installer des modules drupal avec symfony',
                'lieu' =>'paris',
                "date"=> "21-12-2024"
            ]
            ];
    // #[Route('/conferences/{ville}/{id1}/{id2}', name: 'app_conferences', defaults: ['id1'=>'5'], requirements: ['ville'=>'[a-zA-Z]+', 'id2'=>'\d+'])]
    // public function index($id1):Response
    // {
    //    return new Response('le numéro '.$id1);
    // }

    #[Route('/conferences', name:'conference.index')]
    public function index():Response{   
        return $this->render("conferences/conferences.html.twig",['conferences'=>$this->conferences]);
            }


            #[Route('/conference/{id}', name:'conference.details')]
            public function details($id){
                foreach($this->conferences as $cle => $conference){
                    if ($conference['id'] == $id){
                        $conferences[] = $conference;
                    }
                }
            return $this->render("conferences/conferences.html.twig",['conferences'=>$conferences]);
            
            }
}
