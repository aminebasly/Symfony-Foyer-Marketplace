<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MarketplaceController extends AbstractController
{
    #[Route('/marketplace', name: 'app_marketplace')]
    public function index(): Response
    {
        return $this->render('marketplace/index.html.twig', [
            'controller_name' => 'MarketplaceController',
        ]);
    }

    #[Route('/marketplace/produits', name: 'app_etudiant_produit_index')]
    public function index2(ProduitRepository $produitRepository): Response
    {
        // Récupérer tous les produits ou appliquer des filtres si nécessaire
        $produits = $produitRepository->findAll();

        return $this->render('marketplace/etudiant_produit.html.twig', [
            'produits' => $produits,
        ]);

        
    }

    #[Route('/marketplace/produit/{id}', name: 'app_etudiant_produit_show')]
public function show(int $id, ProduitRepository $produitRepository): Response
{
    $produit = $produitRepository->find($id);

    if (!$produit) {
        throw $this->createNotFoundException('Produit non trouvé');
    }

    return $this->render('marketplace/show.html.twig', [
        'produit' => $produit,
    ]);
}
}
