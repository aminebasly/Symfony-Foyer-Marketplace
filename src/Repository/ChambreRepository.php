<?php

namespace App\Repository;

use App\Entity\Chambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chambre>
 */
class ChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambre::class);
    }

    public function searchAndFilter(array $criteria): array
    {
        $qb = $this->createQueryBuilder('c');

        
        if (!empty($criteria['numChambre'])) {
            $qb->andWhere('c.numChambre LIKE :numChambre')
                ->setParameter('numChambre', '%' . $criteria['numChambre'] . '%');
        }

        
        if (!empty($criteria['etage_min']) && !empty($criteria['etage_max'])) {
            $qb->andWhere('c.etage BETWEEN :etage_min AND :etage_max')
                ->setParameter('etage_min', $criteria['etage_min'])
                ->setParameter('etage_max', $criteria['etage_max']);
        }

        
        if (!empty($criteria['capacite_min']) && !empty($criteria['capacite_max'])) {
            $qb->andWhere('c.capacite BETWEEN :capacite_min AND :capacite_max')
                ->setParameter('capacite_min', $criteria['capacite_min'])
                ->setParameter('capacite_max', $criteria['capacite_max']);
        }

       


        

        return $qb->getQuery()->getResult();
    }

    public function findAvailableChambres(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
{
    return $this->createQueryBuilder('c')
        ->leftJoin('c.reservationChambres', 'r')
        ->andWhere(':startDate NOT BETWEEN r.anneeUniversitaire AND r.dateFinReservation')
        ->andWhere(':endDate NOT BETWEEN r.anneeUniversitaire AND r.dateFinReservation')
        ->orWhere('r.idReservationChambre IS NULL') // Pas de réservation associée
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate)
        ->getQuery()
        ->getResult();
}



}
