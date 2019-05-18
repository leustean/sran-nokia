<?php


namespace App\Tests\Service\Login;


use App\Service\SBTS\FakeSync;
use App\Service\SBTS\Sync;
use App\Service\SBTS\SyncFactory;
use App\Tests\AbstractUnitTest;


class SyncFactoryTest extends AbstractUnitTest {

	public function test_getSyncByEnv_givenEnvIsProd(): void {
		$syncFactory = new SyncFactory('prod');

		self::assertInstanceOf(Sync::class, $syncFactory->getSync());
	}

	public function test_getSyncByEnv_givenEnvIsNotProd(): void {
		$syncFactory = new SyncFactory('dev');

		self::assertInstanceOf(FakeSync::class, $syncFactory->getSync());
	}


}