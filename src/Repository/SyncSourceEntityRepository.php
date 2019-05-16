<?php

namespace App\Repository;

use App\Entity\SyncSourceEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SyncSourceEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SyncSourceEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SyncSourceEntity[]    findAll()
 * @method SyncSourceEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SyncSourceEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SyncSourceEntity::class);
    }

    // /**
    //  * @return SyncSourceEntity[] Returns an array of SyncSourceEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SyncSourceEntity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
