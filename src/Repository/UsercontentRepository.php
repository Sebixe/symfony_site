<?php

namespace App\Repository;

use App\Entity\Usercontent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Usercontent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usercontent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserContent[]    findAll()
 * @method Usercontent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class UsercontentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usercontent::class);
    }

    // /**
    //  * @return Usercontent[] Returns an array of Usercontent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usercontent
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
