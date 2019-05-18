<?php


namespace App\Tests\Cron;


use App\Cron\DataSyncCron;
use App\Entity\DeviceEntity;
use App\Entity\SettingsEntity;
use App\Repository\DeviceEntityRepository;
use App\Repository\SettingsEntityRepository;
use App\Service\SBTS\DeviceManager;
use App\Tests\AbstractUnitTest;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use ReflectionException;
use RuntimeException;

class DataSyncCronTest extends AbstractUnitTest {

	/**
	 * @throws ReflectionException
	 * @throws NonUniqueResultException
	 */
	public function test_run_willMarkRefreshAndNotRefreshedDevices(): void {
		$dataSyncCron = new DataSyncCron();
		$now = new DateTimeImmutable();

		$container = $this->getMockContainer();
		$container->method('get')->willReturnCallback(function ($class) use ($now) {
			switch ($class) {
				case 'doctrine.orm.entity_manager':
					return $this->getMockEntityManager();

				case DeviceEntityRepository::class :
					$firstDevice = new DeviceEntity();
					$firstDevice
						->setSbtsId(1);

					$secondDevice = new DeviceEntity();
					$secondDevice
						->setSbtsId(2);

					$deviceEntityRepository = $this->getMockDeviceEntityRepository();
					$deviceEntityRepository->expects($this->once())->method('findDevicesThatNeedRefresh')->willReturn([$firstDevice]);
					$deviceEntityRepository->expects($this->once())->method('findDevicesThatUseTheDefaultRefreshTime')->willReturn([$secondDevice]);

					return $deviceEntityRepository;

				case DeviceManager::class :
					$deviceManager = $this->getMockDeviceManager();
					$deviceManager->method('refreshDeviceData')->willReturnCallback(static function (DeviceEntity $deviceEntity) {
						if ($deviceEntity->getSbtsId() === 2) {
							throw new RuntimeException('test');
						}
					});

					return $deviceManager;

				case SettingsEntityRepository::class :
					$settingsEntity = new SettingsEntity();
					$settingsEntity->setGlobalRefreshTime($now);
					$settingsEntityRepository = $this->getMockSettingsEntityRepository();
					$settingsEntityRepository->method('findLatest')->willReturn($settingsEntity);

					return $settingsEntityRepository;

				default:
					return null;
			}
		});

		$logger = $this->getMockLogger();
		$logger->expects($this->exactly(2))->method('notice')->with($this->callback(static function ($message) {
			if ($message === 'Updated 1 devices:1') {
				return true;
			}
			if ($message === 'Failed to update 1 devices:2') {
				return true;
			}

			return false;
		}));

		self::assertSame('* * * * *', $dataSyncCron->getCronExpresion()->getExpression());
		$dataSyncCron->setUp($container);
		$dataSyncCron->run($now, $logger);
	}
}