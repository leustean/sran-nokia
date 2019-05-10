<?php


namespace App\Tests\Controller;


use App\Entity\DeviceEntity;
use App\Repository\DeviceEntityRepository;
use ReflectionException;

class MainControllerTest extends AbstractControllerTest {

	public function test_indexAction(): void {
		$client = $this->getClient();

		$client->request('GET', '/');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('a[href="/sbts"]'));
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_sbtsAction(){
		$client = $this->getClient();
		$deviceEntityRepository = $this->getMockBuilder(DeviceEntityRepository::class)->disableOriginalConstructor()->getMock();

		$firstDevice = new DeviceEntity();
		$firstDevice->setSbtsId(1);

		$secondDevice = new DeviceEntity();
		$secondDevice->setSbtsId(5);

		$deviceEntityRepository->method('findBy')->willReturn([
			$firstDevice, $secondDevice
		]);

		$this->getContainer()->set(DeviceEntityRepository::class, $deviceEntityRepository);

		$client->request('GET', '/sbts');
		$response = $client->getResponse();
		$crawler = $client->getCrawler();

		self::assertEquals(200, $response->getStatusCode());
		self::assertCount(1, $crawler->filter('a[href="/sbts/1"]'));
		self::assertCount(1, $crawler->filter('a[href="/sbts/5"]'));
	}



}