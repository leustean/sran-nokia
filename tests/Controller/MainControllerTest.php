<?php


namespace App\Tests\Controller;


use App\Entity\DeviceEntity;
use App\Entity\HardwareModuleEntity;
use App\Entity\UserEntity;
use App\Repository\DeviceEntityRepository;
use App\Service\Login\LoginFactory;
use App\Tests\AbstractIntegrationTest;
use DateTime;
use DateTimeImmutable;
use Exception;
use ReflectionException;

class MainControllerTest extends AbstractIntegrationTest {

	public function test_indexAction(): void {
		$client = $this->getClient();

		$client->request('GET', '/');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('a[href="/sbts"]'));
	}

	/**
	 * @throws Exception
	 */
	public function test_sbtsAction(): void {
		$client = $this->getClient();

		$firstDevice = new DeviceEntity();
		$firstDevice
			->setSbtsId(1)
			->setSbtsOwner('pre@test.com')
			->setRefreshTime(new DateTimeImmutable())
			->setIp('192.168.1.2')
			->setPort('3001')
			->setUser('pre')
			->setPassword('pre.test.pass');

		$secondDevice = new DeviceEntity();
		$secondDevice
			->setSbtsId(5)
			->setSbtsState(true)
			->setSbtsOwner('pre@test.com')
			->setRefreshTime(new DateTimeImmutable())
			->setIp('192.168.1.2')
			->setPort('3001')
			->setUser('pre')
			->setPassword('pre.test.pass');

		$module = new HardwareModuleEntity();
		$module->setType(1);
		$module->setProductName('test');

		$secondDevice->addHardwareModule($module);

		$entityManager = $this->getEntityManager();
		$entityManager->persist($firstDevice);
		$entityManager->persist($secondDevice);
		$entityManager->flush();

		$client->request('POST', '/sbts',
			[
				'device' => [
					'sbtsState' => 1
				],
				'smod' => [
					'productName' => 'test'
				]
			]
		);
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(0, $crawler->filter('a[href="/sbts/1"]'));
		self::assertCount(1, $crawler->filter('a[href="/sbts/5"]'));
		self::assertCount(1, $crawler->filter('select[name="device[sbtsState]"] > option[value="1"][selected]'));
		self::assertCount(1, $crawler->filter('select[name="smod[productName]"] > option[value="test"][selected]'));
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_showSbtsAction_allowAdminToSettings(): void {
		$client = $this->getClient();

		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();
		$deviceEntityRepository = $this->getMockDeviceEntityRepository();

		$loginFactory->method('getLogin')->willReturn($loginService);
		$loginService->method('isLogged')->willReturn(true);
		$loginService->method('isAdmin')->willReturn(true);

		$userEntity = new UserEntity();
		$userEntity->setEmail('test@test.com');
		$userEntity->setIsAdmin(true);
		$loginService->method('getPrincipal')->willReturn($userEntity);

		$this->setService(LoginFactory::class, $loginFactory);

		$device = new DeviceEntity();
		$device->setSbtsId(1);
		$device->setSbtsOwner('other@test.com');

		$deviceEntityRepository->method('getBySbtsId')->willReturn($device);

		$this->setService(DeviceEntityRepository::class, $deviceEntityRepository);

		$client->request('GET', '/sbts/1');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('form[name="device"]'));
	}


	/**
	 * @throws ReflectionException
	 */
	public function test_showSbtsAction_allowOwnerToSettings(): void {
		$client = $this->getClient();

		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();
		$deviceEntityRepository = $this->getMockDeviceEntityRepository();

		$loginFactory->method('getLogin')->willReturn($loginService);
		$loginService->method('isLogged')->willReturn(true);
		$loginService->method('isAdmin')->willReturn(false);

		$userEntity = new UserEntity();
		$userEntity->setEmail('test@test.com');
		$userEntity->setIsAdmin(false);
		$loginService->method('getPrincipal')->willReturn($userEntity);

		$this->setService(LoginFactory::class, $loginFactory);

		$device = new DeviceEntity();
		$device->setSbtsId(1);
		$device->setSbtsOwner('test@test.com');

		$deviceEntityRepository->method('getBySbtsId')->willReturn($device);

		$this->setService(DeviceEntityRepository::class, $deviceEntityRepository);

		$client->request('GET', '/sbts/1');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('form[name="device"]'));
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_showSbtsAction_dontAllowOtherUsersToSettings(): void {
		$client = $this->getClient();

		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();
		$deviceEntityRepository = $this->getMockDeviceEntityRepository();

		$loginFactory->method('getLogin')->willReturn($loginService);
		$loginService->method('isLogged')->willReturn(true);
		$loginService->method('isAdmin')->willReturn(false);

		$userEntity = new UserEntity();
		$userEntity->setEmail('test@test.com');
		$userEntity->setIsAdmin(false);
		$loginService->method('getPrincipal')->willReturn($userEntity);

		$this->setService(LoginFactory::class, $loginFactory);

		$device = new DeviceEntity();
		$device->setSbtsId(1);
		$device->setSbtsOwner('other@test.com');

		$deviceEntityRepository->method('getBySbtsId')->willReturn($device);

		$this->setService(DeviceEntityRepository::class, $deviceEntityRepository);

		$client->request('GET', '/sbts/1');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(0, $crawler->filter('form[name="device"]'));
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_showSbtsAction_formSubmit(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();
		$client->disableReboot();

		$time = new DateTime();
		$time->setTime(6, 40);
		$device = new DeviceEntity();

		$device
			->setSbtsId(1)
			->setSbtsOwner('pre@test.com')
			->setRefreshTime($time)
			->setIp('192.168.1.2')
			->setPort('3001')
			->setUser('pre')
			->setPassword('pre.test.pass');

		$entityManager = $this->getEntityManager();
		$entityManager->persist($device);
		$entityManager->flush();

		$client->request('POST', '/sbts/1', [
			'device' => [
				'sbtsId' => 2,
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
		$crawler = $client->getCrawler();
		self::assertCount(1, $crawler->filter('input[name="device[sbtsId]"][value="2"]'));
		self::assertCount(1, $crawler->filter('input[name="device[sbtsOwner]"][value="test@test.com"]'));
		self::assertCount(1, $crawler->filter('input[name="device[refreshTime][hour]"][value="07"]'));
		self::assertCount(1, $crawler->filter('input[name="device[refreshTime][minute]"][value="25"]'));
		self::assertCount(1, $crawler->filter('input[name="device[ip]"][value="192.168.1.1"]'));
		self::assertCount(1, $crawler->filter('input[name="device[port]"][value="3000"]'));
		self::assertCount(1, $crawler->filter('input[name="device[user]"][value="test"]'));
		self::assertCount(1, $crawler->filter('input[name="device[password]"][value="test.pass"]'));

		$devices = $deviceEntityRepository->findAll();
		self::assertCount(1, $devices);
		$device = $devices[0];
		self::assertSame(2, $device->getSbtsId());
		self::assertSame('test@test.com', $device->getSbtsOwner());
		self::assertSame('07:25', $device->getRefreshTime()->format('h:i'));
		self::assertSame('192.168.1.1', $device->getIp());
		self::assertSame(3000, $device->getPort());
		self::assertSame('test', $device->getUser());
		self::assertSame('test.pass', $device->getPassword());
	}

	public function test_showSbtsAction_formSubmit_shouldNotWorkIfNotLogged(): void {
		$client = $this->getClient();
		$client->disableReboot();

		$time = new DateTime();
		$time->setTime(6, 40);
		$device = new DeviceEntity();

		$device
			->setSbtsId(1)
			->setSbtsOwner('pre@test.com')
			->setRefreshTime($time)
			->setIp('192.168.1.2')
			->setPort('3001')
			->setUser('pre')
			->setPassword('pre.test.pass');

		$entityManager = $this->getEntityManager();
		$entityManager->persist($device);
		$entityManager->flush();

		$client->request('POST', '/sbts/1', [
			'device' => [
				'sbtsId' => 2,
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
		self::assertEquals(200, $response->getStatusCode());

		$devices = $deviceEntityRepository->findAll();
		self::assertCount(1, $devices);
		$device = $devices[0];
		self::assertSame(1, $device->getSbtsId());
		self::assertSame('pre@test.com', $device->getSbtsOwner());
		self::assertSame('06:40', $device->getRefreshTime()->format('h:i'));
		self::assertSame('192.168.1.2', $device->getIp());
		self::assertSame(3001, $device->getPort());
		self::assertSame('pre', $device->getUser());
		self::assertSame('pre.test.pass', $device->getPassword());
	}

	public function test_showSbtsAction_deviceNotFound_shouldRedirect(): void {
		$client = $this->getClient();

		$client->request('GET', '/sbts/1');
		$response = $client->getResponse();

		self::assertEquals(302, $response->getStatusCode());
	}

}