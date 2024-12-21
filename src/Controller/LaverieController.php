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
use App\Repository\UserRepository;

#[Route('/laverie')]
final class LaverieController extends AbstractController{
    #[Route('/laverie/{id}' , name: 'app_laverie_index', methods: ['GET','POST'])]
    public function index($id,UserRepository $userRepository,LaverieRepository $laverieRepository): Response
    {
        $user = $userRepository->find($id);
        $laveries = $laverieRepository->findAll();
        $id = $user->getId();
        return $this->render('useretudiant/laveries.html.twig', [
            'laveries' => $laveries,
            'id' => $id,
            'user' =>$user
        ]);
    }

    #[Route('/laverie1' , name: 'app_laverie_index1', methods: ['GET','POST'])]
    public function index1(LaverieRepository $laverieRepository): Response
    {
        
        $laveries = $laverieRepository->findAll();
       
        return $this->render('laverie/index.html.twig', [
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

    #[Route('/laveriedet/{id}', name: 'laverie_detail')]
    public function detail(Laverie $laverie,Machine $machine, MachineRepository $machineRepository): Response
    {
        $machinesAvecTempsRestant = [];

        $machines = $machineRepository->findBy(['laverie' => $laverie]);
        
        
        foreach ($machines as $machine) {
            $tempsRestant = $machineRepository->getTempsRestant($machine->getId());
            $fin = $machineRepository->getfin($machine->getId());
            $machinesAvecTempsRestant[] = [
                
                'tempsRestant' => $tempsRestant,
                'fin' => $fin
                
            ];
        }
        return $this->render('useretudiant/detail.html.twig', [
            'laverie' => $laverie,
            'machines' => $machines,
            'machine' => $machine,
            'tab' => $machinesAvecTempsRestant,
           
        ]);
    }







    #[Route('/laveriedetR', name: 'laverie_reservationdddd')]
    public function details(
        LaverieRepository $laverieRepository,
       
        MachineRepository $machineRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
      
        $laverieId = $request->query->get('idd');       // Récupère 'idd'
        $machineId = $request->query->get('machineId'); // Récupère 'machineId'
      
        $laverie = $laverieRepository->find($laverieId);
        $machine = $machineRepository->find($machineId);
      
        // Récupérer les machines associées à la laverie
        $machines = $machineRepository->findBy(['laverie' => $laverie]);
    
        // Récupérer la machine spécifiée par machineId
       
    
        
    
        // Créer une instance du formulaire
        $form = $this->createForm(ReservationMachineType::class, $machine);
        $form->handleRequest($request);
    
        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $reservationData = $form->getData();
    
            // Mettre à jour les propriétés de la machine
            $machine->setDureeReserve($reservationData->getDureeReserve());
            $machine->setEstReserve(1);
            $machine->setStatutMachine("en cours");
    
            // Sauvegarder les modifications dans la base de données
            $entityManager->persist($machine);
            $entityManager->flush();
    
            // Rediriger ou afficher un message de confirmation
            $this->addFlash('success', 'Réservation effectuée avec succès !');
            return $this->redirectToRoute('laverie_detail', ['id' => $laverieId]);
        }
    
        // Afficher le formulaire et les machines dans le template
        return $this->render('useretudiant/Reserver.html.twig', [
            
            'form' => $form->createView(),
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

            return $this->redirectToRoute('app_laverie_index1', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('laverie/new.html.twig', [
            'laverie' => $laverie,
            'form' => $form
        
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

            return $this->redirectToRoute('app_laverie_index1', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('app_laverie_index1', [], Response::HTTP_SEE_OTHER);
    }
}
