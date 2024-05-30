<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conference;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profil/conference/{idConf}', name: 'user.conference', defaults:['idConf' =>""])]
    #[Route('/profil/{id}', name: 'user.profil')]
    public function profil(EntityManagerInterface $manager, $id='', $idConf=''): Response
    {
        $commentaires = '';
        // $user = $manager->getRepository(User::class)->find($id);
        $user = $this->getUser();
        $conferences = $manager->getRepository(Conference::class)->findBy(['user'=>$this->getUser()]);
        if(isset($idConf) and !empty($idConf)){
            $conference = $manager->getRepository(Conference::class)->find($idConf);
            $commentaires = $manager->getRepository(Commentaire::class)->findBy(['conference'=>$conference]);
            
        }
        
        return $this->render('user/index.html.twig', [
            'user'=>$user,
            'conferences' => $conferences,
            'commentaires'=>$commentaires
        ]);
    }
}






