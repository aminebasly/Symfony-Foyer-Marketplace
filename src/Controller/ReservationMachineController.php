<?php

namespace App\Controller;

use App\Entity\ReservationMachine;
use App\Form\ReservationMachineType;
use App\Repository\ReservationMachineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation/machine')]
final class ReservationMachineController extends AbstractController{
    #[Route(name: 'app_reservation_machine_index', methods: ['GET'])]
    public function index(ReservationMachineRepository $reservationMachineRepository): Response
    {
        return $this->render('reservation_machine/index.html.twig', [
            'reservation_machines' => $reservationMachineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_machine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationMachine = new ReservationMachine();
        $form = $this->createForm(ReservationMachineType::class, $reservationMachine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationMachine);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_machine/new.html.twig', [
            'reservation_machine' => $reservationMachine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_machine_show', methods: ['GET'])]
    public function show(ReservationMachine $reservationMachine): Response
    {
        return $this->render('reservation_machine/show.html.twig', [
            'reservation_machine' => $reservationMachine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_machine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationMachine $reservationMachine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationMachineType::class, $reservationMachine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_machine/edit.html.twig', [
            'reservation_machine' => $reservationMachine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_machine_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationMachine $reservationMachine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationMachine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservationMachine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_machine_index', [], Response::HTTP_SEE_OTHER);
    }
}
