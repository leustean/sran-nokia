<?php


namespace App\Tests\Cron\Prototype;


use App\Cron\Prototype\CronCommand;
use App\Cron\Prototype\CronInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommandTest extends TestCase {

	/**
	 * @throws ReflectionException
	 */
	public function test_startAndFinishTimesAreLogged_givenThereAreNoExceptions(): void {
		$cronId = 'test-cron';
		$outputInterface = $this->getMockOutputInterface();
		$outputInterface
			->expects($this->exactly(2))
			->method('writeln')
			->withConsecutive(
				[$this->stringEndsWith("Cron[{$cronId}] started.")],
				[$this->stringEndsWith("Cron[{$cronId}] finished.")]
			);
		$cronCommand = $this->getCronCommand();
		$cronInterface = $this->getMockCronInterface();
		$cronInterface->method('getId')->willReturn($cronId);

		$this->callProtectedMethod($cronCommand,'runCron',[$cronInterface, $outputInterface]);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_startAndFailTimesAreLogged_givenTheCronThrowsAnException(): void {
		$cronId = 'test-cron';
		$cronExceptionMessage = 'test-message';

		$outputInterface = $this->getMockOutputInterface();
		$outputInterface
			->expects($this->exactly(2))
			->method('writeln')
			->withConsecutive(
				[$this->stringEndsWith("Cron[{$cronId}] started.")],
				[$this->stringEndsWith("Cron[{$cronId}] failed with message: {$cronExceptionMessage}.")]
			);
		$cronCommand = $this->getCronCommand();
		$cronInterface = $this->getMockCronInterface();
		$cronInterface->method('getId')->willReturn($cronId);
		$cronInterface->method('run')->willThrowException(new Exception($cronExceptionMessage));

		$this->callProtectedMethod($cronCommand,'runCron',[$cronInterface, $outputInterface]);
	}

	/**
	 * @return MockObject|CronCommand
	 */
	protected function getCronCommand(): MockObject {
		return $this->getMockForAbstractClass(CronCommand::class);
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

	/**
	 * @param       $object
	 * @param       $method
	 * @param array $args
	 * @return mixed
	 * @throws ReflectionException
	 */
	protected function callProtectedMethod($object, $method, array $args=[]) {
		$class = new ReflectionClass(get_class($object));
		$method = $class->getMethod($method);
		$method->setAccessible(true);
		return $method->invokeArgs($object, $args);
	}
}