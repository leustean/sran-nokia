<?php

namespace App\Repository;

use App\Entity\SampleEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SampleEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SampleEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SampleEntity[]    findAll()
 * @method SampleEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SampleEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SampleEntity::class);
    }

    // /**
    //  * @return SampleEntity[] Returns an array of SampleEntity objects
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
    public function findOneBySomeField($value): ?SampleEntity
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
