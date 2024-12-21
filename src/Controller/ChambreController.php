<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chambre')]
final class ChambreController extends AbstractController
{
    #[Route(name: 'app_chambre_index', methods: ['GET'])]
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chambre);
            $entityManager->flush();

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{idChambre}', name: 'app_chambre_show', methods: ['GET'])]
    public function show(Chambre $chambre): Response
    {
        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    #[Route('/{idChambre}/edit', name: 'app_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{idChambre}', name: 'app_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getIdChambre(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route("/front/chambrey", name: "app_front_chambre")]
    public function frontChambre(Request $request, ChambreRepository $chambreRepository): Response
    {
        $searchTerms = [
            'numChambre' => $request->query->get('numChambre', ''),
            'etage_min' => $request->query->get('etage_min', 1),
            'etage_max' => $request->query->get('etage_max', 10),
            'capacite_min' => $request->query->get('capacite_min', 1),
            'capacite_max' => $request->query->get('capacite_max', 5),
            
        ];

        $chambres = $chambreRepository->searchAndFilter($searchTerms);

        return $this->render('chambre/app_frontchambre.html.twig', [
            'chambres' => $chambres,
            'searchTerms' => $searchTerms,
             
        ]);
   
    }
    private ChambreRepository $chambreRepository;

    // Injecter le repository dans le constructeur
    public function __construct(ChambreRepository $chambreRepository)
    {
        $this->chambreRepository = $chambreRepository;
    }

    /**
     * Action pour effectuer la recherche et le filtrage des chambres.
     *
     *@param Request $request
     * @return Response
     */
    /*#[Route("recherche", name: "app_front_chambre")]
    public function searchAndFilter(Request $request): Response
    {
        // Récupérer les critères de filtrage depuis les paramètres de la requête HTTP
        $criteria = [
            'numChambre' => $request->query->get('numChambre'), // Numéro de chambre
            'etage_min' => $request->query->get('etage_min'),   // Étages minimum
            'etage_max' => $request->query->get('etage_max'),   // Étages maximum
            'capacite_min' => $request->query->get('capacite_min'), // Capacité minimum
            'capacite_max' => $request->query->get('capacite_max'), // Capacité maximum
        ];

        // Appeler la méthode searchAndFilter dans le repository pour obtenir les chambres filtrées
        $chambres = $this->chambreRepository->searchAndFilter($criteria);

        // Retourner une réponse (vous pouvez aussi envoyer les résultats en JSON si nécessaire)
        return $this->render('chambre/recherche_chambres.html.twig', [
            'chambres' => $chambres
        ]);
    }*/
    #[Route("/search", name: "app_front_chambre1")]
    public function search(Request $request, ChambreRepository $chambreRepository): Response
    {
        $searchTerms = [
            'numChambre' => $request->query->get('numChambre', ''),
            'etage_min' => $request->query->get('etage_min', 1),
            'etage_max' => $request->query->get('etage_max', 10),
            'capacite_min' => $request->query->get('capacite_min', 1),
            'capacite_max' => $request->query->get('capacite_max', 5),
            
        ];

        $chambres = $chambreRepository->searchAndFilter($searchTerms);

        return $this->render('chambre/recherche_chambres.html.twig', [
            'chambres' => $chambres,
            'searchTerms' => $searchTerms,
             
        ]);
    }
}
