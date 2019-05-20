<?php /** @noinspection PhpRedundantCatchClauseInspection */


namespace App\Service\Login;


use App\Entity\LoginEntity;
use App\Entity\UserEntity;
use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use ErrorException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LdapLogin implements LoginInterface {

	private const PRINCIPAL_SESSION_INDEX_NAME = 'principal';

	/**
	 * @var SessionInterface
	 */
	private $session;

	/**
	 * @var UserEntityRepository
	 */
	private $userEntityRepository;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var UserEntity
	 */
	private $userEntity;

	public function __construct(SessionInterface $session, UserEntityRepository $userEntityRepository, EntityManagerInterface $entityManager) {
		$this->session = $session;
		$this->userEntityRepository = $userEntityRepository;
		$this->entityManager = $entityManager;
		$this->userEntity = $session->get(self::PRINCIPAL_SESSION_INDEX_NAME);
	}

	/**
	 * @param LoginEntity $loginEntity
	 * @throws LoginException
	 * @throws NonUniqueResultException
	 */
	public function authenticate(LoginEntity $loginEntity): void {
		$ldapConnection = $this->connectToDomain($loginEntity);
		$this->authenticateUser($loginEntity, $ldapConnection);
		$this->setAuthenticatedUser($loginEntity);
	}

	/**
	 * @return UserEntity|null
	 */
	public function getPrincipal(): ?UserEntity {
		return $this->userEntity;
	}

	/**
	 * @return bool
	 */
	public function isLogged(): bool {
		return $this->userEntity !== null;
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool {
		if ($this->userEntity !== null) {
			return $this->userEntity->getIsAdmin();
		}

		return false;
	}

	public function logOut(): void {
		$this->setUserEntity(null);
	}

	/**
	 * @param LoginEntity $loginEntity
	 * @throws NonUniqueResultException
	 */
	protected function setAuthenticatedUser(LoginEntity $loginEntity): void {
		$userEntity = $this->findUserEntity($loginEntity);
		if (!$userEntity) {
			$userEntity = $this->persistNewUser($loginEntity);
		}
		$this->setUserEntity($userEntity);
	}

	/**
	 * @param $userEntity
	 */
	private function setUserEntity(?UserEntity $userEntity): void {
		$this->session->set(self::PRINCIPAL_SESSION_INDEX_NAME, $userEntity);
		$this->userEntity = $userEntity;
	}

	/**
	 * @param LoginEntity $loginEntity
	 * @return UserEntity
	 * @throws NonUniqueResultException
	 */
	private function findUserEntity(LoginEntity $loginEntity): ?UserEntity {
		return $this->userEntityRepository->findOneByEmail($loginEntity->getEmail());
	}

	/**
	 * @param LoginEntity $loginEntity
	 * @return UserEntity
	 * @throws NonUniqueResultException
	 */
	private function persistNewUser(LoginEntity $loginEntity): UserEntity {
		$userEntity = new UserEntity();
		$userEntity
			->setEmail($loginEntity->getEmail())
			->setIsAdmin(false);
		if (!$this->userEntityRepository->adminUserExists()) {
			$userEntity->setIsAdmin(true);
		}

		$this->entityManager->persist($userEntity);
		$this->entityManager->flush();
		return $userEntity;
	}

	/**
	 * @param LoginEntity $loginEntity
	 * @return resource
	 * @throws LoginException
	 */
	private function connectToDomain(LoginEntity $loginEntity) {
		$ldapConnection = ldap_connect($loginEntity->getDomain());
		try {
			if (!$ldapConnection) {
				throw new LoginException('Could not connect to domain');
			}
		} catch (ErrorException $e) {
			throw new LoginException('Could not connect to domain');
		}
		return $ldapConnection;
	}

	/**
	 * @param LoginEntity $loginEntity
	 * @param             $ldapConnection
	 * @throws LoginException
	 */
	private function authenticateUser(LoginEntity $loginEntity, $ldapConnection): void {
		try {
			if (!ldap_bind(
				$ldapConnection,
				$loginEntity->getEmail(),
				$loginEntity->getPassword())
			) {
				throw new LoginException('Invalid credentials');
			}
		} catch (ErrorException $e) {
			throw new LoginException('Could not connect to domain');
		}
	}
}