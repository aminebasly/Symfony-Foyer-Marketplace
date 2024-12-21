<?php

namespace App\Repository;

use App\Entity\Machine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateTimeInterface;

/**
 * @extends ServiceEntityRepository<Machine>
 */
class MachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Machine::class);
    }
    public function getTempsRestant(int $machineId): string
    {
        // Récupérer la machine par son ID
        $machine = $this->find($machineId);
    
        // Vérification que la machine existe et que la durée est valide
        $dureeMinutes = $machine->getDureeReserve();
        if (!$machine || !$dureeMinutes || !is_numeric($dureeMinutes)) {
            return "La machine est disponible.";
        }
    
        // Obtenir la date actuelle
        $dateActuelle = new \DateTime();
    
        // Calculer la date de fin en ajoutant dureeReserve à la date actuelle
        $finReservation = clone $dateActuelle;
        $finReservation->modify("+$dureeMinutes minutes");
    
        // Calculer l'intervalle restant
        $intervalle = $dateActuelle->diff($finReservation);
    
        // Convertir l'intervalle en minutes restantes
        $minutesRestantes = ($intervalle->days * 24 * 60) + ($intervalle->h * 60) + $intervalle->i;
    
        // Retourner le résultat formaté
        if ($minutesRestantes > 60) {
            $heures = floor($minutesRestantes / 60);
            $minutes = $minutesRestantes % 60;
            return "Temps restant : {$heures} heures et {$minutes} minutes";
        } elseif ($minutesRestantes > 0) {
            return "Temps restant : {$minutesRestantes} minutes";
        } else {
            return "La machine est disponible.";
        }
    }


    public function getFin(int $machineId): string
{
    // Récupérer la machine par son ID
    $machine = $this->find($machineId);

    // Vérification que la machine existe et que la durée est valide
    $dureeMinutes = $machine->getDureeReserve();
    if (!$machine || !$dureeMinutes || !is_numeric($dureeMinutes)) {
        return "La machine est disponible.";
    }

    // Obtenir la date actuelle
    $dateActuelle = new \DateTime();

    // Calculer la date de fin en ajoutant dureeReserve à la date actuelle
    $finReservation = clone $dateActuelle;
    $finReservation->modify("+$dureeMinutes minutes");

    // Retourner la date de fin formatée
    return $finReservation->format('Y-m-d H:i:s'); // Format au choix
}

    


    function RechercheType(){
        $em=$this->getEntityManager();
        $req=$em->createQuery('
        select count(m) from App\Entity\Machine m
        where m.typeMachine=:t
        ')
        ->setParameter('sechage','lavage');
        return $req->getSingleScalarResult();
    }

    public function searchMachineByTypeAndStatus(?string $typeMachine, ?string $statusMachine): array
{
    $qb = $this->createQueryBuilder('m'); // 'm' est l'alias pour Machine
    
    // Filtre par type de machine si le paramètre est fourni
    if ($typeMachine) {
        $qb->andWhere('m.typeMachine LIKE :type')
           ->setParameter('type', '%' . $typeMachine . '%');
    }
    
    // Filtre par statut de la machine (par exemple, 'réservée' ou 'disponible') si le paramètre est fourni
    if ($statusMachine) {
        if ($statusMachine === 'reserved') {
            $qb->andWhere('m.estReserve = :statusMachine')
               ->setParameter('statusMachine', true); // La machine est réservée
        } elseif ($statusMachine === 'available') {
            $qb->andWhere('m.estReserve = :statusMachine')
               ->setParameter('statusMachine', false); // La machine n'est pas réservée
        }
    }
    
    // Filtre pour afficher les machines qui ne sont pas expirées, si vous en avez un champ d'expiration
    $qb->andWhere('m.heureDebut > :now') 
       ->setParameter('now', new \DateTime());
    
    // Trier par heure de début ou toute autre colonne pertinente pour votre cas
    $qb->orderBy('m.heureDebut', 'ASC');
    
    // Exécution de la requête
    return $qb->getQuery()->getResult();
}

//    /**
//     * @return Machine[] Returns an array of Machine objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Machine
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
