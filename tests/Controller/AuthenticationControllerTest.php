<?php


namespace App\Tests\Controller;


use App\Entity\LoginEntity;
use App\Entity\UserEntity;
use App\Repository\UserEntityRepository;
use App\Service\Login\FakeLogin;
use App\Service\Login\LoginFactory;
use Exception;
use ReflectionException;

class AuthenticationControllerTest extends AbstractControllerTest {

	protected const EMAIL = 'test@test.com';

	protected const PASS = 'i.am.a.password';

	protected const DOMAIN = 'test.com';

	public function test_loginAction_doesNotRedirectIfUserIsNotLogged(): void {
		$client = $this->getClient();

		$client->request('GET', '/login');

		self::assertEquals(200, $client->getResponse()->getStatusCode());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_loginAction_redirectsIfUserIsLoggedAndNotAdmin(): void {
		$client = $this->getClient();

		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();
		$loginService->method('isLogged')->willReturn(true);
		$loginService->method('isAdmin')->willReturn(false);
		$loginFactory->method('getLogin')->willReturn($loginService);

		$this->setService(LoginFactory::class, $loginFactory);

		$client->request('GET', '/login');
		self::assertEquals(302, $client->getResponse()->getStatusCode());

		$client->followRedirect();
		self::assertEquals(200, $client->getResponse()->getStatusCode());
		$url = $client->getContainer()->get('router')->generate('main_index', [], false);
		self::assertStringEndsWith($url, $client->getRequest()->getUri());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_loginAction_redirectsIfUserIsLoggedAndAdmin(): void {
		$client = $this->getClient();
		$client->disableReboot();
		$this->setMockLoginFactory();

		$client->request('GET', '/login');

		self::assertEquals(302, $client->getResponse()->getStatusCode());

		$client->followRedirect();
		self::assertEquals(200, $client->getResponse()->getStatusCode());
		$url = $client->getContainer()->get('router')->generate('admin_index', [], false);
		self::assertStringEndsWith($url, $client->getRequest()->getUri());
	}

	/**
	 * @throws Exception
	 */
	public function test_loginAction_willRedirectWithValidCredentials_andIsNotAdmin(): void {
		$client = $this->getClient();
		$client->disableReboot();

		$loginFactory = $this->getMockLoginFactory();

		/**
		 * @var UserEntityRepository $userEntityRepository
		 */
		$userEntityRepository = $this->getService(UserEntityRepository::class);

		/** @noinspection PhpParamsInspection */
		$loginService = new FakeLogin(
			$this->getSession(),
			$userEntityRepository,
			$this->getEntityManager()
		);

		$loginFactory->method('getLogin')->willReturn($loginService);

		$this->setService(LoginFactory::class, $loginFactory);

		$client->request('POST', '/login', [
			'login' => [
				'email' => self::EMAIL,
				'password' => self::PASS,
				'domain' => self::DOMAIN
			]
		]);

		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
		$client->followRedirect();

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		$url = $client->getContainer()->get('router')->generate('main_index', [], false);
		self::assertStringEndsWith($url, $client->getRequest()->getUri());

		$users = $userEntityRepository->findAll();
		self::assertSame(count($users), 1);
		$user = $users[0];
		self::assertSame($user->getEmail(),'test@test.com');
	}

	/**
	 * @throws Exception
	 */
	public function test_loginAction_willRedirectWithValidCredentials_andIsAdmin(): void {
		$client = $this->getClient();
		$client->disableReboot();

		$loginFactory = $this->getMockLoginFactory();
		/** @noinspection PhpParamsInspection */
		$loginService = new FakeLogin(
			$this->getSession(),
			$this->getService(UserEntityRepository::class),
			$this->getEntityManager()
		);

		$loginFactory->method('getLogin')->willReturn($loginService);

		$userEntity = new UserEntity();
		$userEntity->setEmail('test@test.com');
		$userEntity->setIsAdmin(true);

		$entityManager = $this->getEntityManager();
		$entityManager->persist($userEntity);
		$entityManager->flush();

		$this->setService(LoginFactory::class, $loginFactory);

		$client->request('POST', '/login', [
			'login' => [
				'email' => self::EMAIL,
				'password' => self::PASS,
				'domain' => self::DOMAIN
			]
		]);

		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
		$client->followRedirect();

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		$url = $client->getContainer()->get('router')->generate('admin_index', [], false);
		self::assertStringEndsWith($url, $client->getRequest()->getUri());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_logoutAction(): void {
		$client = $this->getClient();
		$client->disableReboot();

		$loginFactory = $this->getMockLoginFactory();
		/** @noinspection PhpParamsInspection */
		$loginService = new FakeLogin(
			$this->getSession(),
			$this->getService(UserEntityRepository::class),
			$this->getEntityManager()
		);

		$loginEntity = new LoginEntity();
		$loginEntity
			->setEmail(self::EMAIL)
			->setPassword(self::PASS)
			->setDomain(self::DOMAIN);
		$loginService->authenticate($loginEntity);

		$loginFactory->method('getLogin')->willReturn($loginService);

		$client->request('GET', '/logout');

		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
		$client->followRedirect();

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		$url = $client->getContainer()->get('router')->generate('login_index', [], false);
		self::assertStringEndsWith($url, $client->getRequest()->getUri());
	}

}