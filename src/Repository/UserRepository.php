<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
<<<<<<< HEAD
=======
public function countTotalUsers(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->getQuery()
        ->getSingleScalarResult();
}
public function countPendingUsers(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->where('u.statut = :statut')
        ->setParameter('statut', 'en attente')
        ->getQuery()
        ->getSingleScalarResult();
}
public function countDoctors(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->join('u.role', 'r')
        ->where('r.nom = :role')
        ->setParameter('role', 'medecin')
        ->getQuery()
        ->getSingleScalarResult();
}
public function countCaregivers(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->join('u.role', 'r')
        ->where('r.nom = :role')
        ->setParameter('role', 'soignant')
        ->getQuery()
        ->getSingleScalarResult();
}
public function getDoctorAndPatientPercentage(): array
{
    $totalUsers = $this->countTotalUsers();

    $doctorCount = $this->countDoctors();
    $patientCount = $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->join('u.role', 'r')
        ->where('r.nom = :role')
        ->setParameter('role', 'patient')
        ->getQuery()
        ->getSingleScalarResult();

    return [
        'doctorPercentage' => $totalUsers > 0 ? ($doctorCount / $totalUsers) * 100 : 0,
        'patientPercentage' => $totalUsers > 0 ? ($patientCount / $totalUsers) * 100 : 0,
    ];
}
public function getPendingAndApprovedPercentage(): array
{
    $totalUsers = $this->countTotalUsers();

    $pendingCount = $this->countPendingUsers();
    $approvedCount = $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->where('u.statut = :statut')
        ->setParameter('statut', 'approuvé')
        ->getQuery()
        ->getSingleScalarResult();

    return [
        'pendingPercentage' => $totalUsers > 0 ? ($pendingCount / $totalUsers) * 100 : 0,
        'approvedPercentage' => $totalUsers > 0 ? ($approvedCount / $totalUsers) * 100 : 0,
    ];
}
public function statistics(UserRepository $userRepository): Response
{
    $totalUsers = $userRepository->countTotalUsers();
    $doctors = $userRepository->countDoctors();
    $caregivers = $userRepository->countCaregivers();

    return $this->render('admin/statistics.html.twig', [
        'totalUsers' => $totalUsers,
        'doctors' => $doctors,
        'caregivers' => $caregivers,
    ]);
}

   
>>>>>>> e6eab44440763c3ca3bdb10fb74d6719702effdb


}
