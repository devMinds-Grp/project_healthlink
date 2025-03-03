<?php

namespace App\Repository;

use App\Entity\Reclamation;
use App\Enum\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    //    /**
    //     * @return Reclamation[] Returns an array of Reclamation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reclamation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findBySearchQueryAndStatusQuery(?string $query, ?Status $status): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r');
    
        if ($query) {
            $qb->andWhere('r.titre LIKE :query OR r.description LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }
    
        if ($status) {
            $qb->andWhere('r.Statut = :status')
               ->setParameter('status', $status);
        }
    
        return $qb;
    }
}
