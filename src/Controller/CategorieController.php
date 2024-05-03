<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie/add', name: 'add_categorie')]
    public function add(EntityManagerInterface $manager, Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isvalid()){
            $manager->persist($form->getData());
            $manager->flush();
            $this->redirectToRoute('list_categorie');
        }

        return $this->render('categorie/add.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    #[Route('/categorie/list', name: 'list_categorie')]
    public function list(EntityManagerInterface $manager){
       return new Response('les categories');
        $categories = $manager->getRepository(Categorie::class)->findAll();
        return $this->render("categories/list.html.twig",['categories'=>$categories]);
    }

}
