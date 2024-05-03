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
  
    public $em;
    public function __construct(EntityManagerInterface $manager){
        $this->em = $manager;
    }

    // le nom d'une route doit être unique
    #[Route('/conferences', name: 'conference.index')]
    public function index(): Response
    {
        // $manager->getRepository(Conference::class)->findAll() permet de recuperer toutes les conférences
     $conferences = $this->em->getRepository(Conference::class)->findAll();
     // ici on retourne le template conferences.html.twig par rapport aux parametres passés en arguments
     return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences]);
    }

    #[Route('/conference/details/{id}', name: 'conference.details', requirements: ['id' => '\d+'])]
    public function details($id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conferences[] = $conference;
        //     }
        // }

        $conference = $this->em->getRepository(Conference::class)->find($id);

        return $this->render("conferences/conference.html.twig", ['conference' => $conference]);
    }

    #[Route('/conference/edit/{id}', name: 'conference.edit')]
    public function editer($id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conference['titre'] = 'conference diango';
        //         $conferences[] = $conference;
        //     }
        // }
        $conference = $this->em->getRepository(Conference::class)->find($id);
        $conference->setTitre('teste de titre');
        // on utilise seulement le flush() si on veut supprimer ou modifier
        // si on utilise persist() et flush(), on rajoutera une autre ligne dans la table
        $this->manager->flush();
        
        return $this->redirectToRoute('conference.index'); 
    }
    #[Route('/conference/supp/{id}', name: 'conference.supp')]
    public function delete($id)
    {
        $conference = $this->em->getRepository(Conference::class)->find($id);
        $this->em->remove($conference); // pour supprimer
        $this->em->flush();
        return $this->redirectToRoute('conference.index'); 
    }

    #[Route('/conference/add', name: 'conference.add')]
    public function add()
    {
        // si on a cliqué sur le bouton submit (cela veut dire que le tableau n'est pas vide)
        // si l'utilisateur à posté
         if(!empty($_POST)){
            $conference = new Conference();
            $conference->setTitre($_POST['titre'])
                ->setDescription($_POST['description'])
                ->setLieu($_POST['lieu']);
            $this->em->persist($conference); // enregistrer
            $this->em->flush(); // valider l'enregistrement
        }else{
            return $this->render("conferences/formulaire.html.twig");
        }
        return $this->redirectToRoute("conference.index");
    }
}
