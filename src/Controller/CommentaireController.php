<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire/ajout/{id}', name: 'ajout_commentaire', defaults: ['id' => ''])]
    public function ajoutComment(ValidatorInterface $validator ,Request $request, EntityManagerInterface $manager, $id): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        //    $data =  $form->getData();
           $conference=  $manager->getRepository(Conference::class)->find($id);
           $commentaire->setConference($conference);
        //    dd($data, $commentaire);
            $donnees = $form->getData();
            // dd($commentaire);
            $manager->persist($commentaire);
            $manager->flush();
            return $this->redirectToRoute('conference.details', ['id' => $commentaire->getConference()->getId()]);
        }

        return $this->render('commentaire/ajout.html.twig', [
            'id' => $id,
            'form' => $form->createView()
        ]);
    }
}
