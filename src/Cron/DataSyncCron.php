<?php


namespace App\Cron;


use App\Entity\DeviceEntity;
use App\Entity\SettingsEntity;
use App\Repository\DeviceEntityRepository;
use App\Repository\SettingsEntityRepository;
use App\Service\SBTS\DeviceManager;
use Cron\CronExpression;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataSyncCron implements CronInterface {

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	/**
	 * @var DeviceManager
	 */
	protected $deviceManager;

	/**
	 * @var DeviceEntityRepository
	 */
	protected $deviceEntityRepository;

	/**
	 * @var SettingsEntity|null
	 */
	protected $settingsEntity;

	/**
	 * DataSyncCron constructor.
	 * @param ContainerInterface $container
	 * @throws NonUniqueResultException
	 */
	public function setUp(ContainerInterface $container): void {
		$this->entityManager = $container->get('doctrine.orm.entity_manager');
		$this->deviceManager = $container->get(DeviceManager::class);
		$this->deviceEntityRepository = $container->get(DeviceEntityRepository::class);
		$this->settingsEntity = $container->get(SettingsEntityRepository::class)->findLatest();
	}

	/**
	 * The name of the cron, should only contain a-z characters and "-"
	 * @return string
	 */
	public function getId(): string {
		return 'perform-sbts-data-sync';
	}

	/**
	 * @return CronExpression
	 */
	public function getCronExpresion(): CronExpression {
		return CronExpression::factory('* * * * *');
	}

	/**
	 * The main method of the cron
	 * @param DateTimeImmutable $now
	 * @param LoggerInterface   $logger
	 */
	public function run(DateTimeImmutable $now, LoggerInterface $logger): void {
		$devices = $this->deviceEntityRepository->findDevicesThatNeedRefresh($now) ?: [];

		$globalRefreshTime = $this->settingsEntity ? $this->settingsEntity->getGlobalRefreshTime() : null;
		if ($globalRefreshTime && $now->format('H:i') === $globalRefreshTime->format('H:i')) {
			$devices = array_merge($devices, $this->deviceEntityRepository->findDevicesThatUseTheDefaultRefreshTime() ?: []);
		}

		$devicesUpdate = [];
		$devicesNotUpdate = [];

		foreach ($devices as $device) {
			/**
			 * @var DeviceEntity $device
			 */
			try{
				$this->deviceManager->refreshDeviceData($device);
				$this->entityManager->persist($device);
				$devicesUpdate[] = $device->getSbtsId();
			}catch (Exception $e){
				$devicesNotUpdate += $device->getSbtsId();
			}
		}

		$this->entityManager->flush();

		$devicesUpdateCount = count($devicesUpdate);
		$devicesNotUpdateCount = count($devicesNotUpdate);
		if($devicesUpdateCount !== 0){
			$logger->notice("Updated {$devicesUpdateCount} devices:" . implode(',', $devicesUpdate));
		}
		if($devicesNotUpdateCount !== 0){
			$logger->notice("Failed to update {$devicesNotUpdateCount} devices:" . implode(', ', $devicesNotUpdate));
		}
	}
}