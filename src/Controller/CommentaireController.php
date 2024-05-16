<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire/ajout', name: 'ajout_commentaire')]
    public function ajoutComment(Request $request, EntityManagerInterface $manager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();
            // dd($commentaire);
            $manager->persist($commentaire);
            $manager->flush();
            return $this->redirectToRoute('conference.details',['id'=>$commentaire->getConference()->getId()]);
        }

        return $this->render('commentaire/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
