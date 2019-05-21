<?php

namespace App\Repository;

use App\Entity\SettingsEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SettingsEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SettingsEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SettingsEntity[]    findAll()
 * @method SettingsEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingsEntityRepository extends ServiceEntityRepository {

	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, SettingsEntity::class);
	}

	/**
	 * @return SettingsEntity|null
	 * @throws NonUniqueResultException
	 */
	public function findLatest(): ?SettingsEntity {
		return $this->createQueryBuilder('settings_entity')
			->orderBy('settings_entity.id', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
	}
}
