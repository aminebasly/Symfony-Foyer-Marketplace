<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MachineRepository;
use App\Repository\UserRepository;

class UseretudiantController extends AbstractController
{

    
    #[Route('/useretudiant/{id}', name: 'app_useretudiant', methods: ['GET', 'POST'])]
    public function index($id,UserRepository $userRepository, MachineRepository $mr): Response
    {
        $user = $userRepository->find($id);

        
        return $this->render('useretudiant/index.html.twig', [
            'controller_name' => 'UseretudiantController',
            'id' => $id,
                'nom' => $user->getNom(),
                'prenom'=>$user->getPrenom(),
                'machines' =>$mr->findAll()
            
        ]);
    }
    #[Route("/mon-compte", name:"app_user_account")]
    public function account(): Response
    {
        return $this->render('useretudiant/mon_compte.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
    #[Route("/ma-chambre", name:"app_user_room")]
    public function room(): Response
    {
        return $this->render('useretudiant/ma_chambre.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    // #[Route("/laveries", name:"app_user_laundrys")]
    // public function laundry( MachineRepository $mr): Response
    // {
    //     return $this->render('useretudiant/laveries.html.twig', [
    //         'machines' =>$mr->findAll()
    //     ]);
    // }

    #[Route("/maintenance", name:"app_user_maintenance")]
    public function maintenance(): Response
    {
        return $this->render('useretudiant/maintenance.html.twig', [
            // Ajoutez ici les réclamations de l'utilisateur
        ]);
    }

    #[Route("/marketplace", name:"app_user_marketplace")]
     
    public function marketplace(): Response
    {
        return $this->render('useretudiant/marketplace.html.twig', [
            // Ajoutez ici les données sur les commandes en cours
        ]);
    }
}
