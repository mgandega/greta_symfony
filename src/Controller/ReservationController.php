<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function index(EntityManagerInterface $manager, Request $request,$id): Response
    {
        $conference = $manager->getRepository(Conference::class)->find($id);
        $reservation =  new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $reservation = $form->getData();
            $reservation->setConference($conference);
            $reservation->setUser($this->getUser());
            
            $res = $manager->getRepository(Reservation::class)->findBy(['user'=>$this->getUser(),'conference'=>$reservation->getConference()]);
            // dd($res);
            if( count($res) > 0){
                $this->addFlash('failure','Vous avez déja réserver pour cette conference');
                return $this->redirectToRoute('app_reservation',['id'=>$id]);
            }
            $manager->persist($reservation);
            $manager->flush();

            return $this->redirectToRoute('conference.index');  

        }
        return $this->render('reservation/index.html.twig',['form' => $form->createView()]);
    }
}
