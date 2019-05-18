<?php


namespace App\Tests\Service\SBTS;


use App\Entity\DeviceEntity;
use App\Service\SBTS\DataMapper;
use App\Service\SBTS\SyncException;
use App\Tests\AbstractUnitTest;

class DataMapperTest extends AbstractUnitTest {

	/**
	 * @throws SyncException
	 */
	public function test_setData_throwsExceptionIfNoMappingIsFound(): void {
		$this->expectException(SyncException::class);
		$this->expectExceptionMessage('No mapping found for procedure [some-procedure]');

		$dataMapper = new DataMapper();

		$deviceEntity = new DeviceEntity();

		$dataMapper->setData($deviceEntity, ['some-procedure'], []);
	}

	/**
	 * @throws SyncException
	 */
	public function test_setData_throwsExceptionIfNoDataIsSetForMapping(): void {
		$this->expectException(SyncException::class);
		$this->expectExceptionMessage('No data found for procedure [getBtsInformation]');

		$dataMapper = new DataMapper();

		$deviceEntity = new DeviceEntity();

		$dataMapper->setData($deviceEntity, ['getBtsInformation'], []);
	}

}