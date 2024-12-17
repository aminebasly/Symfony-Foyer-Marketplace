<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Chambre;

class ChambreController extends AbstractController
{
    #[Route('/chambre', name: 'app_chambre')]
    public function index(): Response
    {
        return $this->render('chambre/index.html.twig', [
            'controller_name' => 'ChambreController',
        ]);
    }


//public function assignChambreToUser(User $user, Chambre $chambre): void
//{
   // $user->setChambre($chambre);
  //  $this->entityManager->persist($user);
//    $this->entityManager->flush();
//}
}
