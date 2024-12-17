<?php

namespace App\Controller;

use App\Entity\Laverie;
use App\Form\LaverieType;
use App\Entity\Machine;
use App\Form\ReservationMachineType;
use App\Repository\LaverieRepository;
use App\Repository\MachineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/laverie')]
final class LaverieController extends AbstractController{
    #[Route(name: 'app_laverie_index', methods: ['GET'])]
    public function index(LaverieRepository $laverieRepository): Response
    {
        $laveries = $laverieRepository->findAll();

        return $this->render('useretudiant/laveries.html.twig', [
            'laveries' => $laveries,
        ]);
    }
    #[Route('/laveries', name: 'laveries')]
    public function inde(LaverieRepository $laverieRepository): Response
    {
        $laveries = $laverieRepository->findAll();

        return $this->render('laverie/index.html.twig', [
            'laveries' => $laveries,
        ]);
    }

    #[Route('/laverie/{id}', name: 'laverie_detail')]
    public function detail(Laverie $laverie,Machine $machine, MachineRepository $machineRepository): Response
    {
        $machines = $machineRepository->findBy(['laverie' => $laverie]);
        $tempsRestant = $machineRepository->getTempsRestant($machine);
        return $this->render('useretudiant/detail.html.twig', [
            'laverie' => $laverie,
            'machines' => $machines,
            'machine' => $machine,
            'tempsRestant' => $tempsRestant, 
        ]);
    }

    #[Route('/machine/{id}/reserver', name: 'reserver_machine', methods: ['POST'])]
    public function reserver(Machine $machine, Request $request, EntityManagerInterface $em): Response
    {
        
        if ($machine->isEstReserve()) {
            $this->addFlash('error', 'Cette machine est déjà réservée.');
            return $this->redirectToRoute('laverie_detail', ['id' => $machine->getLaverie()->getId()]);
        }

        
        $form = $this->createForm(ReservationMachineType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $data = $form->getData();
            $machine->setEstReserve(true); 
            $machine->setHeureDebut(new \DateTime());
            $machine->setDureeReserve($data['dureeReservee']);
            $em->persist($machine); 
            $em->flush(); 

            $this->addFlash('success', 'Réservation effectuée avec succès.');
            return $this->redirectToRoute('laverie_detail', ['id' => $machine->getLaverie()->getId()]);
        }

        return $this->render('useretudiant/detail.html.twig', [
            'form' => $form->createView(),
        ]);
    }













    #[Route('/new', name: 'app_laverie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $laverie = new Laverie();
        $form = $this->createForm(LaverieType::class, $laverie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($laverie);
            $entityManager->flush();

            return $this->redirectToRoute('app_laverie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('laverie/new.html.twig', [
            'laverie' => $laverie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_laverie_show', methods: ['GET'])]
    public function show(Laverie $laverie): Response
    {
        return $this->render('laverie/show.html.twig', [
            'laverie' => $laverie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_laverie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Laverie $laverie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LaverieType::class, $laverie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_laverie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('laverie/edit.html.twig', [
            'laverie' => $laverie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_laverie_delete', methods: ['POST'])]
    public function delete(Request $request, Laverie $laverie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$laverie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($laverie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_laverie_index', [], Response::HTTP_SEE_OTHER);
    }
}
