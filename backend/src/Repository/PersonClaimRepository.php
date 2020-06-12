<?php

namespace App\Repository;

use App\Entity\PersonClaim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonClaim|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonClaim|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonClaim[]    findAll()
 * @method PersonClaim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonClaimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonClaim::class);
    }

    // /**
    //  * @return PersonClaim[] Returns an array of PersonClaim objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonClaim
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
