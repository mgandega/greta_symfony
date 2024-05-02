<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Conference;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferencsController extends AbstractController
{
    // public $conferences = [
    //     [
    //         'id' => 1,
    //         'titre' => 'conference Symfony',
    //         'description' => 'Conférence où on parlera des evênements et des subscribers',
    //         'lieu' => 'paris',
    //         "date" => "24-05-2024"
    //     ],
    //     [
    //         'id' => 2,
    //         'titre' => 'conference Laravel',
    //         'description' => 'envoi des notifications à temps reels avec symfony messenger',
    //         'lieu' => 'lyon',
    //         "date" => "18-06-2024"
    //     ],
    //     [
    //         'id' => 3,
    //         'titre' => 'conference drupal',
    //         'description' => 'comment installer des modules drupal avec symfony',
    //         'lieu' => 'paris',
    //         "date" => "21-12-2024"
    //     ]
    // ];
    // #[Route('/conferences/{ville}/{id1}/{id2}', name: 'app_conferences', defaults: ['id1'=>'5'], requirements: ['ville'=>'[a-zA-Z]+', 'id2'=>'\d+'])]
    // public function index($id1):Response
    // {
    //    return new Response('le numéro '.$id1);
    // }

    // le nom d'une route doit être unique
    #[Route('/conferences', name: 'conference.index')]
    public function index(EntityManagerInterface $manager): Response
    {
        // $manager->getRepository(Conference::class)->findAll() permet de recuperer toutes les conférences
     $conferences = $manager->getRepository(Conference::class)->findAll();
     // ici on retourne le template conferences.html.twig par rapport aux parametres passés en arguments
     return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences]);
    }

    #[Route('/conference/details/{id}', name: 'conference.details', requirements: ['id' => '\d+'])]
    public function details(EntityManagerInterface $manager, $id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conferences[] = $conference;
        //     }
        // }

        $conference = $this->manager->getRepository(Conference::class)->find($id);

        return $this->render("conferences/conference.html.twig", ['conference' => $conference]);
    }

    #[Route('/conference/edit/{id}', name: 'conference.edit')]
    public function editer(EntityManagerInterface $manager, $id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conference['titre'] = 'conference diango';
        //         $conferences[] = $conference;
        //     }
        // }
        $conference = $manager->getRepository(Conference::class)->find($id);
        $conference->setTitre('teste de titre');
        // on utilise seulement le flush() si on veut supprimer ou modifier
        // si on utilise persist() et flush(), on rajoutera une autre ligne dans la table
        $manager->flush();
        
        return $this->redirectToRoute('conference.index'); 
    }
    #[Route('/conference/supp/{id}', name: 'conference.supp')]
    public function delete(EntityManagerInterface $manager, $id)
    {
        $conference = $manager->getRepository(Conference::class)->find($id);
        $manager->remove($conference); // pour supprimer
        $manager->flush();
        return $this->redirectToRoute('conference.index'); 
    }

    #[Route('/conference/add', name: 'conference.add')]
    public function add(EntityManagerInterface $manager)
    {

        $conference1 = new Conference();
        $conference1->setTitre('conference Symfony')
            ->setDescription('Conférence où on parlera des evênements et des subscribers')
            ->setLieu('paris');
        $conference2 = new Conference();
        $conference2->setTitre('conference Laravel')
            ->setDescription('envoi des notifications à temps reels avec symfony messenger')
            ->setLieu('lyon');
        $conference3 = new Conference();
        $conference3->setTitre('conference drupal')
            ->setDescription('comment installer des modules drupal avec symfony')
            ->setLieu('paris');

        $manager->persist($conference1);
        $manager->persist($conference2);
        $manager->persist($conference3);
        $manager->flush();

        return $this->redirectToRoute("conference.index");
    }



}
