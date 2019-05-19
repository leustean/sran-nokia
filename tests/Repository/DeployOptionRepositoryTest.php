<?php


namespace App\Tests\Repository;


use App\Repository\DeployOptionRepository;
use App\Tests\AbstractUnitTest;

class DeployOptionRepositoryTest extends AbstractUnitTest {

	public function test_findAll(): void {
		$deployOptionRepository = new DeployOptionRepository('test');
		self::assertCount(11, $deployOptionRepository->findAll());
	}

	public function test_findAll_forProdEnv(): void {
		$deployOptionRepository = new DeployOptionRepository('prod');
		self::assertCount(2, $deployOptionRepository->findAll());
	}

	public function test_find(): void {
		$deployOptionRepository = new DeployOptionRepository('test');
		self::assertNull($deployOptionRepository->find(-1));
		$testOption = $deployOptionRepository->find(0);
		self::assertSame(0, $testOption->getId());
		self::assertSame('test', $testOption->getName());
		self::assertSame('ls', $testOption->getCommand());
	}

}