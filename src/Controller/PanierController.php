<?php

namespace App\Controller;

use App\Entity\Conference;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    public function __construct(private RequestStack $requestStack)
    {
    }
    #[Route('/panier', name: 'app_panier')]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        
        $session = $this->requestStack->getSession();
        $conferenceId = $request->request->get('conferenceId');
        $quantite = $request->request->get('quantite');

        if($conferenceId && $quantite){
            $conference = $manager->getRepository(Conference::class)->find($_POST['conferenceId']);
            $monPanier =  $this->traitementPanier($conferenceId, $quantite, $conference->getTitre(), $conference->getDescription(), $conference->getPrix());
        }


        $panier = $this->getPanier();
        $total = $this->calculPrixTotal();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'total'=>$total
        ]);
    }

    // on utilise cette fonction si on ajoute pour la prémière fois un produitz au panier
    public function initialisationPanier()
    {
        $session = $this->requestStack->getSession();
        // si on arrive pour la premiere fois cette condition retournera true
        if (!$session->has('panier')) {

            // array() => []
            $session->set('panier', [
                'conferenceId' => [],
                'titre' => [],
                'quantite' => [],
                'prix' => [],
            ]);
        }

    }

    public function traitementPanier($conferenceId, $quantite, $titre, $description, $prix)
    {
        $session = $this->requestStack->getSession();
        // initialisation du panier pour verifier si on vient d'ajouter une conférence ou pas
        $this->initialisationPanier();
        $panier = $session->get('panier');


        // cette fonction permet de tester si une valeur existe dans un tableau, si oui elle retourne la premiere clé correspondante, si non elle retourne false
        $position = array_search($conferenceId, $panier['conferenceId']);

        // if ($position == true) != $position !== false

        // si le produit existe déja dans le panier
        if ($position !== false) {
            // $panier['quantite'][$position] += $quantite;
            $panier['quantite'][$position] += $quantite;
        } else {
            // on vient juste d'ajouter une conférence au panier
            $panier['conferenceId'][] = $conferenceId;
            $panier['titre'][] = $titre;
            $panier['quantite'][] = $quantite;
            $panier['prix'][] = $prix;
        }

        // mise à jour du panier (en session)
        $session->set('panier', $panier);
    }

    // récupéreration du panier (en session)
    public function getPanier()
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier');
        return  $panier;
    }

    #[Route('/panier_a_supprimer/{conferenceId}', name: 'app_panier_a_supprimer')]
    public function  panier_a_supprimer($conferenceId)
    {
        // suppression
        $this->supprimerProduit($conferenceId);

        // redirection
        return $this->redirectToRoute('app_panier');
    }

    #[Route('supprimerProduit', name:'supprimerProduit')]
    public function supprimerProduit($conferenceId){
        
        $panier = $this->getPanier();
        $position = array_search($conferenceId, $panier['conferenceId']);

        if($position !== false){
            $session = $this->requestStack->getSession();
            array_splice($panier['conferenceId'], $position, 1);
            array_splice($panier['titre'], $position, 1);
            array_splice($panier['quantite'], $position, 1);
            array_splice($panier['prix'], $position, 1);

            // mise à jour de la session panier
            $session->set('panier', $panier);
        }

    }

    public function calculPrixTotal(){
        $panier = $this->getPanier();
        $total = 0;

        foreach($panier['quantite'] as $index => $quantite ){
            $total +=  $quantite * $panier['prix'][$index];
        }

        return $total;

    }
}
