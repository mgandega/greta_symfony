<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function index(ConferenceRepository $conferenceRepository, EntityManagerInterface $manager, Request $request, $id): Response
    {
        // return new Response($id);
        $conference = $conferenceRepository->find($id);
        $reservation =  new Reservation();

        return $this->render('reservation/index.html.twig');
    }
}
