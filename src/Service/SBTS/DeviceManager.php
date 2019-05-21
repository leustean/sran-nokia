<?php


namespace App\Service\SBTS;


use App\Entity\DeviceEntity;

class DeviceManager {

	/**
	 * @var SyncInterface
	 */
	protected $sync;

	/**
	 * @var DataMapper
	 */
	protected $dataMapper;

	public function __construct(SyncFactory $syncFactory, DataMapper $dataMapper) {
		$this->sync = $syncFactory->getSync();
		$this->dataMapper = $dataMapper;
	}

	public function refreshDeviceData(DeviceEntity $deviceEntity): void {
		$this->sync->setDevice($deviceEntity);
		try{
			$this->dataMapper->setData($deviceEntity, $this->sync->getProcedures(), $this->sync->fetchData());
		}catch (SyncException $syncException){
			$deviceEntity->setSbtsStatus(false);
		}
	}

}