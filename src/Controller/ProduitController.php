<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;


final class ProduitController extends AbstractController
{


    #[Route('/produit',name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository,Request $request,
    EntityManagerInterface $entityManager): Response
    {

        $produits = $produitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/produit/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $produit->setImage($newFilename);
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(int $id,Request $request, Produit $produit, ProduitRepository $produitRepository,EntityManagerInterface $entityManager): Response
    {
        //$produit = $this->findProduitOr404($produitRepository, $id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if ($produit->getImage()) {
                    $oldFilePath = $this->getParameter('images_directory') . '/' . $produit->getImage();
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $produit->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/produitt/recherche', name: 'app_produit_recherche')]
    public function recherche(
        Request $request,
        ProduitRepository $produitRepository // Correct repository import
    ): Response {
        // Récupérer les valeurs de la requête pour chaque critère
        $nomProduit = $request->query->get('nomProduit', '');  // Nom du Produit
        $prixMin = $request->query->get('prix_min', ''); // Prix minimum
        $prixMax = $request->query->get('prix_max', ''); // Prix maximum
        $typeProduit = $request->query->get('typeProduit', ''); // Catégorie du Produit

        // Créer un tableau avec les critères
        $searchTerms = [
            'nomProduit' => $nomProduit,
            'prix_min' => $prixMin,
            'prix_max' => $prixMax,
            'typeProduit' => $typeProduit,
        ];

        // Effectuer la recherche et le filtrage via le repository
        $produits = $produitRepository->searchAndFilter($searchTerms);
        $typesProduits = ['Électronique', 'Menu', 'Mobilier', 'Alimentaire','Vetements'];

        // Récupérer toutes les catégories pour la liste déroulante
        
        // Retourner les résultats avec les critères utilisés pour afficher les options du formulaire
        return $this->render('produit/recherche.html.twig', [
            'produits' => $produits,
            'searchTerms' => $searchTerms,
            'typesProduits' => $typesProduits,  // Passer les catégories pour la sélection
        ]);
    }

    /**
 * @Route("/produit/ajouter-panier/{id}", name="app_produit_ajouter_panier", methods={"POST"})
 */
//     #[Route('/produit/ajouter-panier/{id}', name:"app_produit_ajouter_panier", methods: [ 'POST'])]
//     public function ajouterAuPanier(Request $request, Produit $produit, SessionInterface $session): Response
//     {
//     // Récupérer la quantité demandée
//     $quantity = (int) $request->request->get('quantity');

//     // Vérifier si le panier existe dans la session
//     $panier = $session->get('panier', []);

//     // Ajouter ou mettre à jour le produit dans le panier
//     if (isset($panier[$produit->getId()])) {
//         $panier[$produit->getId()]['quantity'] += $quantity;
//     } else {
//         $panier[$produit->getId()] = [
//             'nomProduit' => $produit->getNomProduit(),
//             'prix' => $produit->getPrix(),
//             'quantity' => $quantity,
//         ];
//     }

//     // Sauvegarder le panier dans la session
//     $session->set('panier', $panier);

//     // Ajouter un message de confirmation
//     $this->addFlash('success', 'Produit ajouté au panier avec succès.');

//     return $this->redirectToRoute('app_etudiant_produit_index');
// }
#[Route('/produit/ajouter-panier/{id}', name: "app_produit_ajouter_panier", methods: ['POST'])]
public function ajouterAuPanier(Request $request, Produit $produit, SessionInterface $session): JsonResponse
{
    // Vérifiez si le produit est valide
    if (!$produit) {
        return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé.'], 404);
    }

    // Récupérer ou initialiser le panier dans la session
    $panier = $session->get('panier', []);
    if (!is_array($panier)) {
        $panier = [];
    }

    // Récupérer la quantité demandée (avec minimum 1)
    $quantity = max(1, (int) $request->request->get('quantity', 1));

    // Ajouter ou mettre à jour le produit dans le panier
    $produitId = $produit->getId();
    if (isset($panier[$produitId])) {
        $panier[$produitId]['quantity'] += $quantity;
    } else {
        $panier[$produitId] = [
            'nomProduit' => $produit->getNomProduit(),
            'prix' => $produit->getPrix(),
            'quantity' => $quantity,
        ];
    }

    // Mettre à jour le panier dans la session
    $session->set('panier', $panier);

    // Retourner une réponse JSON avec le contenu actuel du panier
    return new JsonResponse([
        'success' => true,
        'message' => 'Produit ajouté au panier avec succès.',
        'panier' => $panier,
    ]);
}


/**
 * @Route("/panier", name="app_panier_afficher")
 */
#[Route('/produit/panier', name: "app_produit_panier", methods: ['GET'])]
public function afficherPanier(SessionInterface $session): JsonResponse
{
    // Récupérer le panier de la session
    $panier = $session->get('panier', []);

    // Retourner le contenu du panier
    return new JsonResponse([
        'success' => true,
        'panier' => $panier,
    ]);
}

#[Route('/produit/supprimer-panier/{id}', name: "app_produit_supprimer_panier", methods: ['DELETE'])]
public function supprimerDuPanier(SessionInterface $session, int $id): JsonResponse
{
    // Récupérer le panier de la session
    $panier = $session->get('panier', []);

    // Vérifier si le produit existe dans le panier
    if (isset($panier[$id])) {
        unset($panier[$id]);
        $session->set('panier', $panier);

        return new JsonResponse([
            'success' => true,
            'message' => 'Produit supprimé du panier.',
            'panier' => $panier,
        ]);
    }

    return new JsonResponse([
        'success' => false,
        'message' => 'Produit non trouvé dans le panier.',
    ], 404);
}

/**
 * @Route("/produit/commander/{id}", name="app_produit_commander", methods={"GET", "POST"})
 */
#[Route('/produit/commander/{id}', name:"app_produit_commander", methods: [ "GET",'POST'])]
public function commander(Request $request, Produit $produit): Response
{
    if ($request->isMethod('POST')) {
        $quantity = $request->request->get('quantity');
        $address = $request->request->get('address');

        // Vérifier le stock
        if ($quantity > $produit->getStock()) {
            $this->addFlash('danger', 'Quantité demandée supérieure au stock disponible.');
            return $this->redirectToRoute('app_produit_commander', ['id' => $produit->getId()]);
        }

        // Logic de commande (à compléter)
        // Exemple : enregistrer la commande dans la base de données.

        $this->addFlash('success', 'Commande confirmée avec succès !');
        return $this->redirectToRoute('app_etudiant_produit_index');
    }

    return $this->render('marketplace/commander.html.twig', [
        'produit' => $produit,
    ]);
}


}
