<?php


namespace App\Service\SBTS;


use App\Entity\DeviceEntity;

interface SyncInterface {

	public function setDevice(DeviceEntity $deviceEntity) : SyncInterface;

	public function getProcedures() : array;

	public function fetchData() : array;

}