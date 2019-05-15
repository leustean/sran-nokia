<?php


namespace App\Tests\Repository;


use App\Repository\CronRepository;
use App\Tests\AbstractTest;
use ReflectionClass;
use ReflectionException;

class CronRepositoryTest extends AbstractTest {

	/**
	 *
	 * @throws ReflectionException
	 */
	public function test_findAndFindAll(): void {
		$cronRepository = new CronRepository();
		$reflection = new ReflectionClass($cronRepository);
		$reflectionProperty = $reflection->getProperty('installedCronJobs');
		$reflectionProperty->setAccessible(true);

		$cron = $this->getMockCronInterface();
		$cron->method('getId')->willReturn('test-cron');

		$reflectionProperty->setValue($cronRepository, ['test-cron' => $cron]);

		self::assertCount(1, $cronRepository->findAll());
		self::assertNull($cronRepository->find('some-other-cron'));
		self::assertSame($cron, $cronRepository->find('test-cron'));

	}

}