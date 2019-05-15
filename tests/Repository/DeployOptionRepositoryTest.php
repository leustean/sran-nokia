<?php


namespace App\Tests\Repository;


use App\Repository\DeployOptionRepository;
use App\Tests\AbstractTest;

class DeployOptionRepositoryTest extends AbstractTest {

	public function test_findAll(): void {
		$deployOptionRepository = new DeployOptionRepository();
		self::assertCount(11, $deployOptionRepository->findAll());
	}

	public function test_find(): void {
		$deployOptionRepository = new DeployOptionRepository();
		self::assertNull($deployOptionRepository->find(-1));
		$testOption = $deployOptionRepository->find(0);
		self::assertSame(0, $testOption->getId());
		self::assertSame('test', $testOption->getName());
		self::assertSame('ls', $testOption->getCommand());
	}

}