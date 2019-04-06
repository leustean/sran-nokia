<?php


namespace App\Tests\Command\Cron;

use App\Command\Cron\RunDueCommand;
use App\Cron\Prototype\CronInterface;
use App\Repository\CronRepository;
use Cron\CronExpression;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RunDueCommandTest extends TestCase {

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
		$runOneCommand = new RunDueCommand($cronRepository);

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

	/**
	 * @return MockObject|CronExpression
	 */
	protected function getMockCronExpression(): MockObject {
		return $this->getMockBuilder(CronExpression::class)->disableOriginalConstructor()->getMock();
	}

}