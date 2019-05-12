<?php

namespace App\Repository;

use App\Entity\SettingsEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SettingsEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SettingsEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SettingsEntity[]    findAll()
 * @method SettingsEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingsEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SettingsEntity::class);
    }

    // /**
    //  * @return SettingsEntity[] Returns an array of SettingsEntity objects
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
    public function findOneBySomeField($value): ?SettingsEntity
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
