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
        // $panier['idproduit'] = $session->get('idproduit');
        // $panier['titre'] = $session->get('titre');
        // $panier['description'] = $session->get('description');
        // $panier['quantite'] = $session->get('quantite');
        // $panier['prix'] = $session->get('prix');

        // dd($_SESSION['panier']);
        $panier = $_SESSION['panier'];
        // $_SESSION['panier'] = '';
        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'conference' => $conference
        ]);
    }

    // on utilise cette fonction si on ajoute pour la prémière fois un produitz au panier
    public function initialisationPanier()
    {
        // si on arrive pour la premiere fois cette condition retournera true
        if (!isset($_SESSION['panier'])) {
            // $session = $this->requestStack->getSession();
            // $panier = [];
            // $panier['idProduit'] = '';
            // $panier['titre'] = '';
            // $panier['description'] = '';
            // $panier['quantite'] = '';
            // $panier['prix'] = '';
            // // mise en session des données
            // $session->set($panier['idProduit'], "");
            // $session->set($panier['titre'], "");
            // $session->set($panier['description'], "");
            // $session->set($panier['prix'], "");

            // $monPanier['idProduit'][] = $session->get($panier['idProduit']);
            // $monPanier['titre'][] = $session->get($panier['titre']);
            // $monPanier['description'][] = $session->get($panier['description']);
            // $monPanier['quantite'][] = $session->get($panier['quantite']);
            // $monPanier['prix'][] = $session->get($panier['prix']);

            // return $monPanier;
            $_SESSION['panier'] = array();
            $_SESSION['panier']['conferenceId'] = array();
            $_SESSION['panier']['titre'] = array();
            $_SESSION['panier']['quantite'] = array();
            $_SESSION['panier']['prix'] = array();
        }
        //  else {
        //     return $monPanier;
        // }
    }

    public function traitementPanier($conferenceId, $quantite, $titre, $description, $prix)
    {
        $session = $this->requestStack->getSession();
        $this->initialisationPanier();
        $position = array_search($conferenceId, $_SESSION['panier']['conferenceId']);
        // dd($conference);
        // si le produit existe déja dans le panier
        if ($position !== false) {
            // $panier['quantite'][$position] += $quantite;
            $_SESSION['panier']['quantite'][$position] += $quantite;
        } else {
            // si le produit n' existe pas encore dans le panier
            // $panier['idProduit'][] = $conferenceId;
            // $panier['titre'][] = $quantite;
            // $panier['description'][] = $titre;
            // $panier['description'][] = $titre;
            // $panier['quantite'][] = $description;
            // $panier['prix'][]= $prix;

            $_SESSION['panier']['conferenceId'][] = $conferenceId;
            $_SESSION['panier']['titre'][] = $titre;
            $_SESSION['panier']['quantite'][] = $quantite;
            $_SESSION['panier']['prix'][] = $prix;
        }


        // $session = $this->requestStack->getSession();
        // $panier['idProduit'][] =  $session->get($panier['idProduit']);
        // $panier['titre'][] =  $session->get($panier['titre']);
        // $panier['description'][] =  $session->get($panier['description']);
        // $panier['quantite'][] =  $session->get($panier['quantite']);
        // return $panier;

    }
}
