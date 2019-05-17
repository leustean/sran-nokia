<?php


namespace App\Tests\Command\Cron;

use App\Command\Cron\RunDueCommand;
use App\Tests\AbstractUnitTest;
use Exception;


class RunDueCommandTest extends AbstractUnitTest {

	/**
	 * @throws Exception
	 */
	public function test_onlyCronJobsThatAreDueAreRun_someAreAndSomeAreNot(): void {
		$dueCronExpression = $this->getMockCronExpression();
		$dueCronExpression
			->method('isDue')
			->willReturn(true);
		$dueCron = $this->getMockCronInterface();
		$dueCron
			->method('getCronExpresion')
			->willReturn($dueCronExpression);
		$dueCron
			->expects($this->once())
			->method('run');

		$notDueCronExpression = $this->getMockCronExpression();
		$notDueCronExpression
			->method('isDue')
			->willReturn(false);
		$notDueCron = $this->getMockCronInterface();
		$notDueCron
			->method('getCronExpresion')
			->willReturn($notDueCronExpression);
		$notDueCron
			->expects($this->never())
			->method('run');

		$cronRepository = $this->getMockCronRepository();
		$cronRepository
			->method('findAll')
			->willReturn([$dueCron, $notDueCron]);

		$runDueCommand = new RunDueCommand($cronRepository, $this->getMockContainer());

		$runDueCommand->run($this->getMockInputInterface(), $this->getMockOutputInterface());
	}

	/**
	 * @throws Exception
	 */
	public function test_startAndFinishTimesAreLogged_givenThereAreNoExceptions(): void {
		$cronId = 'test-cron';

		$dueCronExpression = $this->getMockCronExpression();
		$dueCronExpression
			->method('isDue')
			->willReturn(true);
		$dueCron = $this->getMockCronInterface();
		$dueCron
			->method('getCronExpresion')
			->willReturn($dueCronExpression);
		$dueCron
			->method('getId')
			->willReturn($cronId);
		$dueCron
			->expects($this->once())
			->method('run');

		$outputInterface = $this->getMockOutputInterface();
		$outputInterface
			->expects($this->exactly(2))
			->method('writeln')
			->withConsecutive(
				[$this->stringEndsWith("Cron[{$cronId}] started.")],
				[$this->stringEndsWith("Cron[{$cronId}] finished.")]
			);

		$cronRepository = $this->getMockCronRepository();
		$cronRepository
			->method('findAll')
			->willReturn([$dueCron]);
		$runDueCommand = new RunDueCommand($cronRepository, $this->getMockContainer());

		$runDueCommand->run($this->getMockInputInterface(), $outputInterface);
	}

	/**
	 * @throws Exception
	 */
	public function test_startAndFailTimesAreLogged_givenTheCronThrowsAnException(): void {
		$cronId = 'test-cron';
		$cronExceptionMessage = 'test-message';

		$dueCronExpression = $this->getMockCronExpression();
		$dueCronExpression
			->method('isDue')
			->willReturn(true);
		$dueCron = $this->getMockCronInterface();
		$dueCron
			->method('getCronExpresion')
			->willReturn($dueCronExpression);
		$dueCron
			->method('getId')
			->willReturn($cronId);
		$dueCron
			->method('run')
			->willThrowException(new Exception($cronExceptionMessage));

		$outputInterface = $this->getMockOutputInterface();
		$outputInterface
			->expects($this->exactly(2))
			->method('writeln')
			->withConsecutive(
				[$this->stringEndsWith("Cron[{$cronId}] started.")],
				[$this->stringEndsWith("Cron[{$cronId}] failed with message: {$cronExceptionMessage}.")]
			);

		$cronRepository = $this->getMockCronRepository();
		$cronRepository
			->method('findAll')
			->willReturn([$dueCron]);
		$runDueCommand = new RunDueCommand($cronRepository, $this->getMockContainer());

		$runDueCommand->run($this->getMockInputInterface(), $outputInterface);
	}

}