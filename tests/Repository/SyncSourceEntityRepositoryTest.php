<?php


namespace App\Tests\Repository;


use App\Repository\SyncSourceEntityRepository;
use App\Tests\AbstractIntegrationTest;

class SyncSourceEntityRepositoryTest extends AbstractIntegrationTest {

	public function test_methods(): void {
		$deviceEntityRepository = $this->getService(SyncSourceEntityRepository::class);
		self::assertInstanceOf(SyncSourceEntityRepository::class, $deviceEntityRepository);
	}

}