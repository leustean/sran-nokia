<?php


namespace App\Tests\Command\Cron;

use App\Command\Cron\RunOneCommand;
use App\Cron\Prototype\CronInterface;
use App\Repository\CronRepository;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RunOneCommandTest extends TestCase {

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Cron not found
	 * @throws Exception
	 */
	public function test_exceptionIsThrown_givenTheCronIsNotFound(): void {
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

	/**
	 * @return MockObject|CronRepository
	 */
	protected function getMockCronRepository(): MockObject {
		return $this->getMockBuilder(CronRepository::class)->getMock();
	}

	/**
	 * @return MockObject|InputInterface
	 */
	protected function getMockInputInterface(): MockObject {
		return $this->getMockBuilder(InputInterface::class)->getMock();
	}

	/**
	 * @return MockObject|OutputInterface
	 */
	protected function getMockOutputInterface(): MockObject {
		return $this->getMockBuilder(OutputInterface::class)->getMock();
	}

	/**
	 * @return MockObject|CronInterface
	 */
	protected function getMockCronInterface(): MockObject {
		return $this->getMockBuilder(CronInterface::class)->getMock();
	}

}