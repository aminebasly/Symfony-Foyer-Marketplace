<?php

namespace App\Repository;

use App\Entity\Bloc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bloc>
 */
class BlocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bloc::class);
    }

    //    /**
    //     * @return Bloc[] Returns an array of Bloc objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bloc
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function getStatistiquesChambres(int $idBloc): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select(
                'COUNT(c.idChambre) AS totalChambres',
                'SUM(c.capacite) AS capaciteTotale',
                'AVG(c.capacite) AS capaciteMoyenne',
                'SUM(CASE WHEN c.capacite < 5 THEN 1 ELSE 0 END) AS chambresDisponibles'
            )
            ->leftJoin('b.chambres', 'c') 
            ->where('b.idBloc = :idBloc')
            ->setParameter('idBloc', $idBloc) 
            ->groupBy('b.idBloc'); 

        
        $result = $qb->getQuery()->getSingleResult();

        return [
            'totalChambres' => (int) $result['totalChambres'],
            'capaciteTotale' => (int) $result['capaciteTotale'],
            'capaciteMoyenne' => (float) $result['capaciteMoyenne'],
            'chambresDisponibles' => (int) $result['chambresDisponibles']
        ];
    }

    public function findBlocsSortedByCapacity(): array
{
    return $this->createQueryBuilder('b')
        ->orderBy('b.capaciteBloc', 'DESC')
        ->getQuery()
        ->getResult();
}




    

    
}
