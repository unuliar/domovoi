<?php

namespace App\Repository;

use App\Entity\PersonRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonRequest[]    findAll()
 * @method PersonRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonRequest::class);
    }

    // /**
    //  * @return PersonRequest[] Returns an array of PersonRequest objects
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
    public function findOneBySomeField($value): ?PersonRequest
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
