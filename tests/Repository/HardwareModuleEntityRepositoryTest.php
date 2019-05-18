<?php


namespace App\Tests\Repository;


use App\Repository\HardwareModuleEntityRepository;
use App\Tests\AbstractIntegrationTest;

class HardwareModuleEntityRepositoryTest extends AbstractIntegrationTest {
	
	public function test_methods(): void {
		$deviceEntityRepository = $this->getService(HardwareModuleEntityRepository::class);
		self::assertInstanceOf(HardwareModuleEntityRepository::class, $deviceEntityRepository);
	}

}