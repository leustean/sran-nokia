<?php

namespace App\Repository;

use App\Entity\HardwareModuleEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HardwareModuleEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method HardwareModuleEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method HardwareModuleEntity[]    findAll()
 * @method HardwareModuleEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HardwareModuleEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HardwareModuleEntity::class);
    }

    // /**
    //  * @return ModulesEntity[] Returns an array of ModulesEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModulesEntity
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
