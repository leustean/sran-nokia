<?php


namespace App\Tests\Command\Cron;

use App\Command\Cron\RunOneCommand;
use App\Cron\Prototype\CronInterface;
use App\Repository\CronRepository;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RunOneCommandTest extends TestCase {

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

	/**
	 * @return MockObject|CronRepository
	 * @throws ReflectionException
	 */
	protected function getMockCronRepository(): MockObject {
		return $this->getMockBuilder(CronRepository::class)->getMock();
	}

	/**
	 * @return MockObject|InputInterface
	 * @throws ReflectionException
	 */
	protected function getMockInputInterface(): MockObject {
		return $this->getMockBuilder(InputInterface::class)->getMock();
	}

	/**
	 * @return MockObject|OutputInterface
	 * @throws ReflectionException
	 */
	protected function getMockOutputInterface(): MockObject {
		return $this->getMockBuilder(OutputInterface::class)->getMock();
	}

	/**
	 * @return MockObject|CronInterface
	 * @throws ReflectionException
	 */
	protected function getMockCronInterface(): MockObject {
		return $this->getMockBuilder(CronInterface::class)->getMock();
	}

}