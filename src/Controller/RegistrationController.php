<?php

namespace App\Controller;

use App\Entity\User;
use App\Events\InscriptionConferenceEvent;
use App\Form\RegistrationFormType;
use App\RegisterMail;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(EventDispatcherInterface $dispatcher, RegisterMail $registerMail, Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $firstname = $user->getFirstName();
            $lastname = $user->getLastName();
            $email = $user->getEmail();
            // $registerMail->send($firstname,$lastname,$email);
            // onInscriptionConferenceEvent
            $event = new InscriptionConferenceEvent($user);
            $dispatcher->dispatch($event);
            // do anything else you need here, like send an email
            $this->addFlash('success','Inscription réussie');
            return $this->redirectToRoute('conference.index');
            // return $security->login($user, SecurityAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
