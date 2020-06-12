<?php

namespace App\Repository;

use App\Entity\AccountPollResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountPollResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountPollResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountPollResult[]    findAll()
 * @method AccountPollResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountPollResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountPollResult::class);
    }

    // /**
    //  * @return AccountPollResult[] Returns an array of AccountPollResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountPollResult
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
