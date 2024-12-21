<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CommandeType;
use App\Form\ProduitType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;


final class CommandeController extends AbstractController
{
    #[Route('/commande',name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/commande/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/commande/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/commande/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/commande/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export/pdf', name: 'pdf', methods: ['GET'])]
public function listA(CommandeRepository $commandeRepository): Response
{
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($pdfOptions);

    // Récupérer le contenu HTML à partir du template Twig
    $html = $this->renderView('commande/pdf.html.twig', [
        'commandes' => $commandeRepository->findAll(),
    ]);

    // Charger le HTML dans Dompdf
    $dompdf->loadHtml($html);

    // Définir le format de la page et l'orientation
    $dompdf->setPaper('A4', 'portrait');

    // Générer le PDF
    $dompdf->render();

    // Récupérer le contenu du PDF généré
    $pdfOutput = $dompdf->output();

    // Créer une réponse Symfony avec le contenu PDF
    $response = new Response($pdfOutput, Response::HTTP_OK);

    // Définir les entêtes pour indiquer qu'il s'agit d'un fichier PDF à télécharger
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="mypdf.pdf"');

    return $response;
}

#[Route('/passerCommande', name: 'passer_commande')]
public function passerCommande(EntityManagerInterface $entityManager, Request $request): Response
{
    // Récupérer le panier depuis la session
    $panier = $request->getSession()->get('panier', []);
    $total = 0;

    // Créer une nouvelle commande
    $commande = new Commande();
    $commande->setDateCommande(new \DateTime());
    $commande->setStatutCommande('En attente');

    // Ajouter les produits de la commande
    foreach ($panier as $id => $item) {
        // Vérifier si l'élément du panier est bien un tableau avec les clés nécessaires
        if (is_array($item) && isset($item['prix'], $item['quantity'])) {
            $produit = $entityManager->getRepository(Produit::class)->find($id);
            if ($produit) {
                // Ajouter le produit à la commande avec la quantité
                $commande->addProduit($produit, $item['quantity']);
                $total += $item['prix'] * $item['quantity'];
            }
        } else {
            // Ajouter un log ou gérer l'élément invalide si nécessaire
            // Exemple : ignorer les éléments malformés
            continue;
        }
    }

    $commande->setTotalCommande($total);

    // Enregistrer la commande dans la base de données
    $entityManager->persist($commande);
    $entityManager->flush();

    // Vider le panier après la commande
    $request->getSession()->remove('panier');

    // Rediriger vers une page de confirmation ou d'accueil
    return $this->redirectToRoute('confirmation_commande', ['id' => $commande->getId()]);
}

#[Route('/confirmation-commande/{id}', name: 'confirmation_commande')]
public function confirmationCommande(int $id,Commande $commande,EntityManagerInterface $entityManager): Response
{
    $commande = $entityManager->getRepository(Commande::class)->find($id);

    if (!$commande) {
        throw $this->createNotFoundException("La commande avec l'ID $id n'existe pas.");
    }

    return $this->render('marketplace/confirmation_commande.html.twig', [
        'commande' => $commande,
    ]);
}
}
