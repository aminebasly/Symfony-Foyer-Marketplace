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
    public function getTempsRestant(Machine $machine): ?string
    {
        
        if ($machine->getHeureDebut() === null || $machine->getDureeReserve() === null) {
            return null;  
        }
        $heureDebut = $machine->getHeureDebut();

     

        $now = new DateTime();
        $endTime = clone $heureDebut; 
        $endTime->modify("+{$machine->getDureeReserve()} minutes");

        if ($now > $endTime) {
            return 'Expired'; 
        }

       
        $remainingTime = $now->diff($endTime);
        return $remainingTime->format('%H:%I:%S'); 
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
