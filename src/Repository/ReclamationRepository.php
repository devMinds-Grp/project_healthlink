<?php

namespace App\Repository;

use App\Entity\Reclamation;
<<<<<<< HEAD
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
=======
use App\Enum\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
>>>>>>> e6eab44440763c3ca3bdb10fb74d6719702effdb
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
<<<<<<< HEAD
=======

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
>>>>>>> e6eab44440763c3ca3bdb10fb74d6719702effdb
}
