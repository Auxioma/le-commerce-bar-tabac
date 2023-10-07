<?php

namespace App\Repository;

use App\Entity\UserJeux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserJeux>
 *
 * @method UserJeux|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserJeux|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserJeux[]    findAll()
 * @method UserJeux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserJeuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserJeux::class);
    }

//    /**
//     * @return UserJeux[] Returns an array of UserJeux objects
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

//    public function findOneBySomeField($value): ?UserJeux
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
