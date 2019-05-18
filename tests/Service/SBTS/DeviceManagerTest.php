<?php


namespace App\Tests\Service\SBTS;


use App\Entity\DeviceEntity;
use App\Service\SBTS\DataMapper;
use App\Service\SBTS\DeviceManager;
use App\Service\SBTS\SyncException;
use App\Service\SBTS\SyncFactory;
use App\Tests\AbstractUnitTest;
use ReflectionException;

class DeviceManagerTest extends AbstractUnitTest {

	public function test_refreshDeviceData_setsAllTheDataAvailable(): void {
		$deviceManager = new DeviceManager(new SyncFactory('test'), new DataMapper());

		$device = new DeviceEntity();

		$deviceManager->refreshDeviceData($device);

		self::assertSame('MRMOCK', $device->getSbtsHwConfiguration());
		self::assertSame('SBTS_MOCK_MOCK_MOCK_MOCK', $device->getSbtsSwRelease());
		self::assertNull($device->getLteState());
		self::assertSame('ALL_ENABLED', $device->getWcdmaState());
		self::assertSame('NO_CELLS', $device->getGsmState());
		self::assertSame('MRMOCK', $device->getSbtsHwConfiguration());
		self::assertCount(7, $device->getHardwareModules());
		self::assertCount(1, $device->getSyncSources());
		self::assertCount(2, $device->getActiveAlarms());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_refreshDeviceData_setDeviceStatusToFalseIfItFails(): void {
		$dataMapper = $this->getMockDataMapper();
		$dataMapper->method('setData')->willThrowException(new SyncException('test'));

		$syncFactory = $this->getMockSyncFactory();
		$syncFactory->method('getSync')->willReturn($this->getMockSync());

		$deviceManager = new DeviceManager($syncFactory, $dataMapper);

		$device = new DeviceEntity();

		$deviceManager->refreshDeviceData($device);

		self::assertFalse($device->getSbtsStatus());
	}

}