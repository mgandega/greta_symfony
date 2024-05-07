<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Categorie;
use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\CategorieRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ConferencesController extends AbstractController
{

    public $em;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    // le nom d'une route doit être unique
    #[Route('/conferences', name: 'conference.index')]
    #[Route('/conferences/categorie/{id}', name: 'conference.categorie')]
    public function index(Request $request, CategorieRepository $categorie): Response
    {
        // dd($request->attributes->get('id'));

        // $manager->getRepository(Conference::class)->findAll() permet de recuperer toutes les conférences
        if ($request->attributes->get('id')) {
            $conferences = $this->em->getRepository(Conference::class)->findBy(['categorie' => $request->attributes->get('id')]);
        } else {
            $conferences = $this->em->getRepository(Conference::class)->findAll();
        }
        // ici on retourne le template conferences.html.twig par rapport aux parametres passés en arguments
        $categories = $categorie->findAll();
        return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences, 'categories' => $categories]);
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
    public function editer(Request $request, $id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conference['titre'] = 'conference diango';
        //         $conferences[] = $conference;
        //     }
        // }

        $conference = $this->em->getRepository(Conference::class)->find($id);

        $form = $this->createForm(ConferenceType::class, $conference, ['button_label' => 'Modifier une conférence']);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            $conference = $form->getData();
            // on utilise seulement le flush() si on veut supprimer ou modifier
            // si on utilise persist() et flush(), on rajoutera une autre ligne dans la table
            $this->em->flush();

            return $this->redirectToRoute('conference.index');
        }
        return $this->render("conferences/edit.html.twig", ['form' => $form->createView(), 'bouton' => 'modifier']);
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
    public function add(Request $request)
    {
        // dd($_SERVER['DOCUMENT_ROOT'].'uploads/images');
        $dossier_images = $_SERVER['DOCUMENT_ROOT'].'uploads/images';

        // si on a cliqué sur le bouton submit (cela veut dire que le tableau n'est pas vide)
        // si l'utilisateur à posté
        //  if(!empty($_POST)){
        //     $conference = new Conference();
        //     $conference->setTitre($_POST['titre'])
        //         ->setDescription($_POST['description'])
        //         ->setLieu($_POST['lieu']);
        //     $this->em->persist($conference); // enregistrer
        //     $this->em->flush(); // valider l'enregistrement
        // }else{
        //     return $this->render("conferences/formulaire.html.twig");
        // }

        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference, ['button_label' => 'Ajouter une conférence']);

        // ici je lie les données du formulaire avec l'objet conference s'il y'en a
        // il hydrate les propriétés
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            // dd($form->get('file')->getData()->getClientOriginalName());
            // dd($form->getData()->getImage()->getFile()->getClientOriginalName());
            $imagefile = $form->getData()->getImage()->getFile();
            $imageName = $form->getData()->getImage()->getFile()->getClientOriginalName();
            $imagefile->move($dossier_images, $imageName);
            $fichier_bd = $dossier_images.'/'.$imageName;
            $conference = $form->getData();
            $conference->getImage()->setFile($imagefile);
            $conference->getImage()->setUrl($fichier_bd);
            $conference->getImage()->setAlt($imageName);
            $this->em->persist($conference);
            $this->em->flush();

            return $this->redirectToRoute("conference.index");
        }

        return $this->render("conferences/ajout.html.twig", ['form' => $form->createView(), 'bouton' => 'ajouter']);
    }
    // #[Route('/conferences/categorie/{id}', name:'conference.categorie')]
    // public function conferencesParCategorie(ConferenceRepository $conference, $id){
    //     $conferences = $conference->findBy(['categorie'=>$id]);
    //     return $this->render("conferences/conferences.html.twig",['conferences'=>$conferences]);
    // }
    #[Route('/conferences/menu', name: 'conference.menu')]
    public function menu()
    {
        $conferences = $this->em->getRepository(Conference::class)->findAll();
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        return $this->render("conferences/menu.html.twig", ['conferences' => $conferences, 'categories' => $categories]);
    }
}
