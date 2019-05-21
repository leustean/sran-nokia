<?php


namespace App\Tests\Repository;


use App\Entity\DeviceEntity;
use App\Repository\DeviceEntityRepository;
use App\Tests\AbstractIntegrationTest;
use DateTimeImmutable;

class DeviceEntityRepositoryTest extends AbstractIntegrationTest {

	public function test_methods(): void {
		$now = new DateTimeImmutable();

		$firstDevice = new DeviceEntity();
		$firstDevice
			->setSbtsId(1)
			->setSbtsOwner('pre@test.com')
			->setRefreshTime($now)
			->setIp('192.168.1.2')
			->setPort('3001')
			->setUser('pre')
			->setPassword('pre.test.pass');

		$secondDevice = new DeviceEntity();
		$secondDevice
			->setSbtsId(2)
			->setSbtsOwner('pre@test.com')
			->setRefreshTime(null)
			->setIp('192.168.1.2')
			->setPort('3001')
			->setUser('pre')
			->setPassword('pre.test.pass');

		$entityManager = $this->getEntityManager();
		$entityManager->persist($firstDevice);
		$entityManager->persist($secondDevice);
		$entityManager->flush();

		/**
		 * @var DeviceEntityRepository $deviceEntityRepository
		 */
		$deviceEntityRepository = $this->getService(DeviceEntityRepository::class);
		$devicesThatNeedRefresh = $deviceEntityRepository->findDevicesThatNeedRefresh($now);
		self::assertCount(1, $devicesThatNeedRefresh);
		self::assertTrue(isset($devicesThatNeedRefresh[0]));
		self::assertSame($firstDevice->getSbtsId(),$devicesThatNeedRefresh[0]->getSbtsId());

		$devicesWithDefaultRefreshTime = $deviceEntityRepository->findDevicesThatUseTheDefaultRefreshTime();
		self::assertCount(1, $devicesWithDefaultRefreshTime);
		self::assertTrue(isset($devicesWithDefaultRefreshTime[0]));
		self::assertSame($secondDevice->getSbtsId(),$devicesWithDefaultRefreshTime[0]->getSbtsId());
	}

}