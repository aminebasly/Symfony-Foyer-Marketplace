<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function searchAndFilter(array $searchTerms): array
{
    $qb = $this->createQueryBuilder('p'); 

    // Filtrage par nomproduit
    if (!empty($searchTerms['nomProduit'])) {
        $qb->andWhere('p.nomProduit LIKE :nomProduit')
           ->setParameter('nomProduit', '%' . $searchTerms['nomProduit'] . '%');
    }

    // Filtrage par prixMin
    if (!empty($searchTerms['prix_min'])) {
        $qb->andWhere('p.prix >= :prixMin')
           ->setParameter('prixMin', $searchTerms['prix_min']);
    }

    // Filtrage par prixMax
    if (!empty($searchTerms['prix_max'])) {
        $qb->andWhere('p.prix <= :prixMax')
           ->setParameter('prixMax', $searchTerms['prix_max']);
    }

    return $qb->getQuery()->getResult();
}
}
