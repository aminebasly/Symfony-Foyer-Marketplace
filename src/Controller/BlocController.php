<?php

namespace App\Controller;

use App\Entity\Bloc;
use App\Form\BlocType;
use App\Repository\BlocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bloc')]
final class BlocController extends AbstractController
{
    #[Route(name: 'app_bloc_index', methods: ['GET'])]
    public function index(BlocRepository $blocRepository): Response
    {
        return $this->render('bloc/index.html.twig', [
            'blocs' => $blocRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bloc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bloc = new Bloc();
        $form = $this->createForm(BlocType::class, $bloc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bloc);
            $entityManager->flush();

            return $this->redirectToRoute('app_bloc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bloc/new.html.twig', [
            'bloc' => $bloc,
            'form' => $form,
        ]);
    }

    #[Route('/{idBloc}', name: 'app_bloc_show1', methods: ['GET'])]
    public function show(Bloc $bloc): Response
    {
        return $this->render('bloc/show.html.twig', [
            'bloc' => $bloc,
        ]);
    }

    #[Route('/{idBloc}/edit', name: 'app_bloc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bloc $bloc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlocType::class, $bloc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bloc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bloc/edit.html.twig', [
            'bloc' => $bloc,
            'form' => $form,
        ]);
    }

    #[Route('/{idBloc}', name: 'app_bloc_delete', methods: ['POST'])]
    public function delete(Request $request, Bloc $bloc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bloc->getIdBloc(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bloc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bloc_index', [], Response::HTTP_SEE_OTHER);
    }

    /*#[Route('/blocs/{minCapacity}', name: 'app_bloc_by_min_capacity')]
    public function findBlocsByMinCapacity(BlocRepository $blocRepository, int $minCapacity): Response
    {
        $blocs = $blocRepository->findBlocsByMinTotalCapacity($minCapacity);

        
        return $this->render('bloc/index.html.twig', [
            'blocs' => $blocs,
        ]);
    }*/

    #[Route('/bloc/{idBloc}', name: 'bloc_show')]
    public function show1(Bloc $bloc, BlocRepository $blocRepository): Response
    {
        // Récupérer les statistiques du bloc en utilisant le repository
        $statistiques = $blocRepository->getStatistiquesChambres($bloc->getIdBloc());

        return $this->render('bloc/statistiques.html.twig', [
            'bloc' => $bloc,
            'statistiques' => $statistiques,
        ]);
    }
    
    #[Route('/blocs', name: 'blocs_sorted')]
    public function blocsSorted(BlocRepository $repo): Response
    {
        $blocs = $repo->findBlocsSortedByCapacity();

        return $this->render('bloc/sorted.html.twig', [
            'blocs' => $blocs,
        ]);
    }
    #[Route('/front/bloc', name: 'app_bloc_show')]
    public function show2( BlocRepository $blocRepository): Response
    {
        // Récupère tous les blocs
        $blocs = $blocRepository->findAll();

        return $this->render('bloc/app_frontbloc.html.twig', [
            'blocs' => $blocs,
        ]);
    }
}
