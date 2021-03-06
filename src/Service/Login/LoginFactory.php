<?php


namespace App\Service\Login;


use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginFactory {

	/**
	 * @var string
	 */
	private const PROD_ENV = 'prod';

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
	 * @var LoginInterface
	 */
	private $login;

	public function __construct($env, SessionInterface $session, UserEntityRepository $userEntityRepository, EntityManagerInterface $entityManager) {
		$this->session = $session;
		$this->userEntityRepository = $userEntityRepository;
		$this->entityManager = $entityManager;
		$this->login = $this->getLoginByEnv($env);
	}

	/**
	 * @param $env
	 * @return LoginInterface
	 */
	private function getLoginByEnv($env): LoginInterface {
		if ($env === self::PROD_ENV) {
			return new LdapLogin($this->session, $this->userEntityRepository, $this->entityManager);
		}

		return new FakeLogin($this->session, $this->userEntityRepository, $this->entityManager);
	}

	/**
	 * @return LoginInterface
	 */
	public function getLogin(): LoginInterface {
		return $this->login;
	}

}