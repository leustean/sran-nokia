<?php


namespace App\Tests\Controller;


use App\Entity\SettingsEntity;
use App\Repository\SettingsEntityRepository;
use App\Service\Login\LoginFactory;
use DateTime;
use Exception;
use ReflectionException;

class AdminControllerTest extends AbstractControllerTest {

	/**
	 * @throws ReflectionException
	 */
	public function test_adminPages_shouldRedirect_ifUserIsNotLoggedIn(): void {
		$client = $this->getClient();

		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();
		$loginService->method('isLogged')->willReturn(false);
		$loginFactory->method('getLogin')->willReturn($loginService);

		$this->setService(LoginFactory::class, $loginFactory);

		$client->request('GET', '/admin');

		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_adminPages_shouldRedirect_ifUserIsNotAdmin(): void {
		$client = $this->getClient();

		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();
		$loginService->method('isLogged')->willReturn(true);
		$loginService->method('isAdmin')->willReturn(false);
		$loginFactory->method('getLogin')->willReturn($loginService);

		$this->setService(LoginFactory::class, $loginFactory);

		$client->request('GET', '/admin');

		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_adminPages_shouldNotRedirect_ifUserIsAdmin(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();


		$client->request('GET', '/admin');

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_refreshTimeAction(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();
		$settingsEntityRepository = $this->getMockSettingsEnityRepository();

		$settingsEntity = new SettingsEntity();
		$time = new DateTime();
		$time->setTime(5,30);
		$settingsEntity->setGlobalRefreshTime($time);
		$settingsEntityRepository->method('findOneBy')->willReturn($settingsEntity);
		$this->setService(SettingsEntityRepository::class, $settingsEntityRepository);

		$client->request('GET', '/admin/refreshTime');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('input[value="05"]'));
		self::assertCount(1, $crawler->filter('input[value="30"]'));
	}

	/**
	 * @throws Exception
	 */
	public function test_refreshTimeAction_formSubmit_givenValidData(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();
		$client->disableReboot();

		$settingsEntity = new SettingsEntity();
		$time = new DateTime();
		$time->setTime(5,30);
		$settingsEntity->setGlobalRefreshTime($time);

		$entityManager = $this->getEntityManager();
		$entityManager->persist($settingsEntity);
		$entityManager->flush();

		$client->request('POST', '/admin/refreshTime', [
			'global_refresh_time' => [
				'globalRefreshTime' => [
					'hour' => 7,
					'minute' => 25
				]
			]
		]);
		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
		$crawler = $client->followRedirect();
		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('input[value="07"]'));
		self::assertCount(1, $crawler->filter('input[value="25"]'));
	}



}