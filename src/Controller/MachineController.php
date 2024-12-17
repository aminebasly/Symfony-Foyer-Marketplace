<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/machine')]
final class MachineController extends AbstractController{
    #[Route(name: 'app_machine_index', methods: ['GET'])]
    public function index(MachineRepository $machineRepository): Response
    {
        return $this->render('machine/index.html.twig', [
            'machines' => $machineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_machine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($machine);
            $entityManager->flush();

            return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine/new.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_machine_show', methods: ['GET'])]
    public function show(Machine $machine): Response
    {
        return $this->render('machine/show.html.twig', [
            'machine' => $machine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_machine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine/edit.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }
    #[Route('/recherche/{tt}',name:'Recherche')]
    function recherche($tt,Request $request,MachineRepository $repo){
        $tt=$request->get('ss');
        $machines=$repo->findByTypeMachine($tt);
        return $this->render('machine/index.html.twig',
        ['machines'=>$machines]);
       
    }
    #[Route('/export/pdf', name: 'pdf', methods: ['GET'])]
public function listA(MachineRepository $machineRepository): Response
{
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($pdfOptions);

    
    $html = $this->renderView('machine/pdf.html.twig', [
        'machines' => $machineRepository->findAll(),
    ]);

   
    $dompdf->loadHtml($html);

    
    $dompdf->setPaper('A4', 'portrait');

   
    $dompdf->render();

   
    $pdfOutput = $dompdf->output();

    
    $response = new Response($pdfOutput, Response::HTTP_OK);

    
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="mypdf.pdf"');

    return $response;
}
    #[Route("/search", name:"machine_search", methods: ['GET'])]
public function searchMachines(Request $request, MachineRepository $machineRepository): Response
{   
    // Récupère les paramètres "type" et "status" de la requête
    $type = $request->query->get('typeMachine', ''); // Par exemple, type de machine : "séchoir", "machine à laver"
    $status = $request->query->get('statusMachine', ''); // "available" ou "reserved" pour filtrer les machines

    // Appel à la méthode du repository avec les deux paramètres pour filtrer les machines
    $machines = $machineRepository->searchMachineByTypeAndStatus($type, $status);

    // Rend les résultats à une vue Twig
    return $this->render('laverie/machine_search.html.twig', [
        'machines' => $machines,
    ]);
}


    #[Route('/{id}', name: 'app_machine_delete', methods: ['POST'])]
    public function delete(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$machine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($machine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
    }
}
