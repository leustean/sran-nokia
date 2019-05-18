<?php


namespace App\Tests\Repository;


use App\Repository\AlarmEntityRepository;
use App\Tests\AbstractIntegrationTest;

class AlarmEntityRepositoryTest extends AbstractIntegrationTest {
	
	public function test_methods(): void {
		$deviceEntityRepository = $this->getService(AlarmEntityRepository::class);
		self::assertInstanceOf(AlarmEntityRepository::class, $deviceEntityRepository);
	}

}