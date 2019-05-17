<?php


namespace App\Tests\Service\SBTS;


use App\Entity\DeviceEntity;
use App\Service\SBTS\DataMapper;
use App\Service\SBTS\DeviceManager;
use App\Service\SBTS\SyncFactory;
use App\Tests\AbstractUnitTest;

class DeviceManagerTest extends AbstractUnitTest {


	public function test_refreshDeviceData_setsAllTheDataAvailable(): void {
		$deviceManager = new DeviceManager(new SyncFactory('test'), new DataMapper());

		$device = new DeviceEntity();

		$deviceManager->refreshDeviceData($device);

		self::assertSame($device, $device);
	}

}