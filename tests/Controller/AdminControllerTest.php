<?php


namespace App\Tests\Controller;


use App\Entity\SettingsEntity;
use App\Entity\UserEntity;
use App\Repository\DeviceEntityRepository;
use App\Repository\SettingsEntityRepository;
use App\Tests\AbstractIntegrationTest;
use DateTime;
use Exception;
use ReflectionException;

class AdminControllerTest extends AbstractIntegrationTest {

	/**
	 * @throws ReflectionException
	 */
	public function test_indexAction(): void {
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
		$settingsEntityRepository = $this->getMockSettingsEntityRepository();

		$settingsEntity = new SettingsEntity();
		$time = new DateTime();
		$time->setTime(5, 30);
		$settingsEntity->setGlobalRefreshTime($time);
		$settingsEntityRepository->method('findLatest')->willReturn($settingsEntity);
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
		$time->setTime(5, 30);
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

	/**
	 * @throws ReflectionException
	 */
	public function test_addDeviceAction_formSubmit(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();
		$client->disableReboot();

		$client->request('POST', '/admin/add-device', [
			'device' => [
				'sbtsId' => 1,
				'sbtsOwner' => 'test@test.com',
				'refreshTime' => [
					'hour' => 7,
					'minute' => 25
				],
				'ip' => '192.168.1.1',
				'port' => '3000',
				'user' => 'test',
				'password' => 'test.pass'
			]
		]);

		/**
		 * @var DeviceEntityRepository $deviceEntityRepository
		 */
		$deviceEntityRepository = $this->getService(DeviceEntityRepository::class);

		$response = $client->getResponse();
		self::assertEquals(302, $response->getStatusCode());
		$client->followRedirect();
		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());

		$devices = $deviceEntityRepository->findAll();
		self::assertCount(1, $devices);
		$device = $devices[0];
		self::assertSame(1, $device->getSbtsId());
		self::assertSame('test@test.com', $device->getSbtsOwner());
		self::assertSame('07:25', $device->getRefreshTime()->format('h:i'));
		self::assertSame('192.168.1.1', $device->getIp());
		self::assertSame(3000, $device->getPort());
		self::assertSame('test', $device->getUser());
		self::assertSame('test.pass', $device->getPassword());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_usersAction(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();

		$entityManager = $this->getEntityManager();

		$admin = new UserEntity();
		$admin
			->setEmail('admin@test.com')
			->setIsAdmin(true);
		$entityManager->persist($admin);

		$user = new UserEntity();
		$user
			->setEmail('user@test.com')
			->setIsAdmin(false);
		$entityManager->persist($user);

		$entityManager->flush();

		$client->request('GET', '/admin/users');

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		$crawler = $client->getCrawler();
		self::assertCount(1, $crawler->filter('input[name="normalUsers[]"][value="' . $user->getId() . '"]'));
		self::assertCount(1, $crawler->filter('input[name="adminUsers[]"][value="' . $admin->getId() . '"]'));
	}

}
