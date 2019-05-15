<?php


namespace App\Tests\Command\Cron;

use App\Command\Cron\RunOneCommand;
use App\Tests\AbstractTest;
use Exception;
use RuntimeException;


class RunOneCommandTest extends AbstractTest {

	/**
	 * @throws Exception
	 */
	public function test_exceptionIsThrown_givenTheCronIsNotFound(): void {
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Cron not found');

		$cronRepository = $this->getMockCronRepository();
		$cronRepository
			->method('find')
			->willReturn(null);
		$runOneCommand = new RunOneCommand($cronRepository);

		$runOneCommand->run($this->getMockInputInterface(), $this->getMockOutputInterface());
	}

	/**
	 * @throws Exception
	 */
	public function test_cronIsRun_givenItIsFound(): void {
		$cronInterface = $this->getMockCronInterface();
		$cronInterface
			->expects($this->once())
			->method('run');
		$cronRepository = $this->getMockCronRepository();
		$cronRepository
			->method('find')
			->willReturn($cronInterface);
		$runOneCommand = new RunOneCommand($cronRepository);

		$runOneCommand->run($this->getMockInputInterface(), $this->getMockOutputInterface());
	}

}