<?php

namespace App\Controller;

use App\Entity\ReservationChambre;
use App\Form\ReservationChambreType;
use App\Repository\ReservationChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation/chambre')]
final class ReservationChambreController extends AbstractController
{
    #[Route(name: 'app_reservation_chambre_index', methods: ['GET'])]
    public function index(ReservationChambreRepository $reservationChambreRepository): Response
    {
        return $this->render('reservation_chambre/index.html.twig', [
            'reservation_chambres' => $reservationChambreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationChambre = new ReservationChambre();
        $form = $this->createForm(ReservationChambreType::class, $reservationChambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationChambre);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_chambre/new.html.twig', [
            'reservation_chambre' => $reservationChambre,
            'form' => $form,
        ]);
    }

    #[Route('/{idReservationChambre}', name: 'app_reservation_chambre_show', methods: ['GET'])]
    public function show(ReservationChambre $reservationChambre): Response
    {
        return $this->render('reservation_chambre/show.html.twig', [
            'reservation_chambre' => $reservationChambre,
        ]);
    }

    #[Route('/{idReservationChambre}/edit', name: 'app_reservation_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationChambre $reservationChambre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationChambreType::class, $reservationChambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_chambre/edit.html.twig', [
            'reservation_chambre' => $reservationChambre,
            'form' => $form,
        ]);
    }

    #[Route('/{idReservationChambre}', name: 'app_reservation_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationChambre $reservationChambre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationChambre->getIdReservationChambre(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservationChambre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_chambre_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/reservations/{chambreId}/valides', name: 'app_reservations_valides')]
    public function validReservations(int $chambreId, ReservationChambreRepository $reservationChambreRepository): Response
    {
        $reservations = $reservationChambreRepository->findValidReservationsByChambre($chambreId);

        return $this->render('reservation_chambre/valid_reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/reservations/date', name: 'reservations_par_date')]
    public function reservationsParDate(Request $request, ReservationChambreRepository $repo): Response
    {
        $startDate = new \DateTime($request->query->get('start_date', 'now'));
        $endDate = new \DateTime($request->query->get('end_date', 'now +1 month'));

        $reservations = $repo->findReservationsByDateRange($startDate, $endDate);

        return $this->render('reservation_chambre/date.html.twig', [
            'reservations' => $reservations,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

}
