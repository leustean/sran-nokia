<?php

namespace App\Repository;

use App\Entity\DeviceEntity;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviceEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceEntity[]    findAll()
 * @method DeviceEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceEntityRepository extends ServiceEntityRepository {

	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, DeviceEntity::class);
	}

	/**
	 * @param DateTimeInterface $dateTime
	 * @return DeviceEntity[]
	 */
	public function findDevicesThatNeedRefresh(DateTimeInterface $dateTime): array {
		return $this->createQueryBuilder('device_entity')
			->andWhere('TIME(device_entity.refreshTime) = TIME(:refreshTime)')
			->setParameter('refreshTime', $dateTime->format('h:i'))
			->getQuery()
			->getResult();
	}

	/**
	 * @return DeviceEntity[]
	 */
	public function findDevicesThatUseTheDefaultRefreshTime(): array {
		return $this->createQueryBuilder('device_entity')
			->andWhere('device_entity.refreshTime IS NULL')
			->getQuery()
			->getResult();
	}
}
