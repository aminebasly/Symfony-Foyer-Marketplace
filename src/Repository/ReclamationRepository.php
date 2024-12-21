<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    /**
     * Compte le nombre de réclamations par mois.
     *
     * @return array
     */
    public function countByMonth(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('SUBSTRING(r.date_reclamation, 6, 2) as month, COUNT(r.id) as count')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery();

        return $qb->getResult();
    }
}
