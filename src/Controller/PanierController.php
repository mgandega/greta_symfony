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
    public function index(EntityManagerInterface $manager): Response
    {
        $session = $this->requestStack->getSession();
        $conference = $manager->getRepository(Conference::class)->find($_POST['conferenceId']);
        $monPanier =  $this->traitementPanier($_POST['conferenceId'], $_POST['quantite'], $conference->getTitre(), $conference->getDescription(), $conference->getPrix());

        $panier = $this->getPanier();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'conference' => $conference
        ]);
    }

    // on utilise cette fonction si on ajoute pour la prémière fois un produitz au panier
    public function initialisationPanier()
    {
        $session = $this->requestStack->getSession();
        // si on arrive pour la premiere fois cette condition retournera true
        if (!$session->has('panier')) {

            // array() => []
            $session->set('panier',[
                'conferenceId' =>[],
                'titre' =>[],
                'quantite' =>[],
                'prix' =>[],
            ]);
            
        }

    }

    public function traitementPanier($conferenceId, $quantite, $titre, $description, $prix)
    {
        $session = $this->requestStack->getSession();
        // initialisation du panier 
        $this->initialisationPanier();
        $panier = $session->get('panier');

        $position = array_search($conferenceId, $panier['conferenceId']);

        
        // if ($position == true) != $position !== false
        
        // si le produit existe déja dans le panier
        if ($position !== false) {
            // $panier['quantite'][$position] += $quantite;
            $panier['quantite'][$position] += $quantite;
        } else {
            $panier['conferenceId'][] = $conferenceId;
            $panier['titre'][] = $titre;
            $panier['quantite'][] = $quantite;
            $panier['prix'][] = $prix;
        }

        // mise à jour du panier (en session)
        $session->set('panier', $panier);

    }

    public function getPanier(){
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier');
        return  $panier;
    }
}
