<?php


namespace App\Service\SBTS;


use App\Entity\AlarmEntity;
use App\Entity\DeviceEntity;
use App\Entity\HardwareModuleEntity;
use App\Entity\SyncSourceEntity;
use DateTime;
use Exception;

class DataMapper {

	protected static $procedureMapping = [
		'getBtsInformation' => 'mapGeneralDataToDevice',
		'getSynchronizationInformation' => 'mapSyncDataToDevice',
		'getActiveAlarms' => 'mapAlarmDataToDevice'
	];

	/**
	 * @param DeviceEntity $deviceEntity
	 * @param array        $procedures
	 * @param array        $fetchedData
	 * @return DeviceEntity
	 * @throws SyncException
	 */
	public function setData(DeviceEntity $deviceEntity, array $procedures, array $fetchedData): DeviceEntity {
		foreach ($procedures as $procedure) {
			if (!isset(self::$procedureMapping[$procedure])) {
				throw new SyncException("No mapping found for procedure [{$procedure}]");
			}
			if (!isset($fetchedData[$procedure])) {
				throw new SyncException("No data found for procedure [{$procedure}]");
			}
			$mappingFunction = self::$procedureMapping[$procedure];

			$this->$mappingFunction($deviceEntity, $fetchedData[$procedure]);
		}
		$deviceEntity->setSbtsStatus(true);
		$deviceEntity->setLastInformationRefresh(new DateTime());

		return $deviceEntity;
	}

	/**
	 * @param DeviceEntity $deviceEntity
	 * @param              $data
	 */
	protected function mapGeneralDataToDevice(DeviceEntity $deviceEntity, $data): void {
		$data = $data->requestMessage;
		$deviceEntity->setSbtsHwConfiguration($data->id->configuration ?? null);
		$deviceEntity->setSbtsSwRelease($data->swInfo->swVersion ?? null);
		$deviceEntity->setLteState($data->ratState->lte ?? null);
		$deviceEntity->setWcdmaState($data->ratState->wcdma ?? null);
		$deviceEntity->setGsmState($data->ratState->gsm ?? null);
		foreach ($data->systemModules ?? [] as $module) {
			$hardwareModule = $this->mapHardwareModule($module, DeviceEntity::SMOD);
			$deviceEntity->addHardwareModule($hardwareModule);
		}
		foreach ($data->basebandModules ?? [] as $module) {
			$hardwareModule = $this->mapHardwareModule($module, DeviceEntity::BMOD);
			$deviceEntity->addHardwareModule($hardwareModule);
		}
		foreach ($data->radioModules->rmod ?? [] as $module) {
			$hardwareModule = $this->mapHardwareModule($module, DeviceEntity::RFMOD);
			$deviceEntity->addHardwareModule($hardwareModule);
		}
		foreach ($data->radioModules->asirmod ?? [] as $module) {
			$hardwareModule = $this->mapHardwareModule($module, DeviceEntity::RFMOD);
			$deviceEntity->addHardwareModule($hardwareModule);
		}
		$state = $data->status->operationalState ?? null;
		$deviceEntity->setSbtsState($state === null ? null : $state === 'Enabled');
	}

	/**
	 * @param DeviceEntity $deviceEntity
	 * @param              $data
	 */
	protected function mapSyncDataToDevice(DeviceEntity $deviceEntity, $data): void {
		$deviceEntity->clearSyncSources();
		foreach ($data->requestMessage->syncSourceInfo ?? [] as $syncSource) {
			$syncSourceEntity = new SyncSourceEntity();
			$syncSourceEntity->setSyncInputType($syncSource->syncInputType ?? null);
			$syncSourceEntity->setSyncInputPrio($syncSource->syncInputPrio ?? null);
			$syncSourceEntity->setIsActive($syncSource->isActive ?? null);
			$syncSourceEntity->setAvailability($syncSource->status->availability ?? null);
			$syncSourceEntity->setUsability($syncSource->status->usability ?? null);
			$deviceEntity->addSyncSource($syncSourceEntity);
		}
	}

	/**
	 * @param DeviceEntity $deviceEntity
	 * @param              $data
	 * @return void
	 */
	protected function mapAlarmDataToDevice(DeviceEntity $deviceEntity, $data): void {
		$deviceEntity->clearActiveAlarms();
		foreach ($data->requestMessage ?? [] as $alarm) {
			$alarmEntity = new AlarmEntity();
			$alarmEntity->setSeverity($alarm->severity ?? null);
			if (property_exists($alarm, 'observationTime')) {
				try {
					$alarmEntity->setObservationTime(DateTime::createFromFormat('YmdHis.vO',$alarm->observationTime));
				} catch (Exception $e) {
					$alarmEntity->setObservationTime(null);
				}
			}
			$alarmEntity->setAlarmId($alarm->alarmId ?? null);
			$alarmEntity->setFaultId($alarm->faultId ?? null);
			$alarmEntity->setAlarmName($alarm->alarmName ?? null);
			$alarmEntity->setFaultSeverity($alarm->faultSeverity ?? null);
			$alarmEntity->setAlarmDetail($alarm->alarmInformation->alarmDetail ?? null);
			$alarmEntity->setAlarmDetailNumber($alarm->alarmInformation->alarmDetailNbr ?? null);
			$alarmEntity->setFaultDescription($alarm->alarmInformation->faultDescription ?? null);
			$deviceEntity->addActiveAlarm($alarmEntity ?? null);
		}
	}

	/**
	 * @param $data
	 * @param $type
	 * @return HardwareModuleEntity
	 */
	protected function mapHardwareModule($data, $type): HardwareModuleEntity {
		$hardwareModuleEntity = new HardwareModuleEntity();
		$hardwareModuleEntity->setProductCode($data->productCode ?? null);
		$hardwareModuleEntity->setProductName($data->productName ?? null);
		$hardwareModuleEntity->setSerialNumber($data->serialNumber ?? null);
		$hardwareModuleEntity->setUsageState($data->status->usageState ?? null);
		$hardwareModuleEntity->setType($type);

		return $hardwareModuleEntity;
	}

}