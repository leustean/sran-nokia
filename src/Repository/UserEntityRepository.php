<?php

namespace App\Repository;

use App\Entity\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEntity[]    findAll()
 * @method UserEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEntityRepository extends ServiceEntityRepository {
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, UserEntity::class);
	}

	/**
	 * @param string $email
	 * @return UserEntity|null
	 */
	public function findOneByEmail(string $email): ?UserEntity {
		try {
			return $this->createQueryBuilder('user_entity')
				->andWhere('user_entity.email = :email')
				->setParameter('email', $email)
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}

	public function adminUserExists(): bool {
		try {
			$result = $this->createQueryBuilder('user_entity')
				->andWhere('user_entity.isAdmin = 1')
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();

			return $result !== null;
		} catch (NonUniqueResultException $e) {
			return true;
		}
	}
}
