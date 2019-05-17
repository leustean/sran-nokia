<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-04-02
 * Time: 15:54
 */

namespace App\Command\Cron;


use App\Cron\CronInterface;
use App\Repository\CronRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RunDueCommand extends Command {

	/**
	 * @var CronRepository
	 */
	protected $cronRepository;

	/**
	 * @var ContainerInterface
	 */
	protected $container;

	protected function configure(): void {
		$this
			->setName('app:cron:run:due')
			->setDescription('Runs all cron jobs that are due.');
	}

	/**
	 * RunDueCommand constructor.
	 * @param CronRepository     $cronRepository
	 * @param ContainerInterface $container
	 */
	public function __construct(CronRepository $cronRepository, ContainerInterface $container) {
		$this->cronRepository = $cronRepository;
		$this->container = $container;

		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		foreach ($this->cronRepository->findAll() as $cron) {
			if ($cron->getCronExpresion()->isDue()) {
				$this->runCron($cron, $output);
			}
		}
	}

	private function runCron(CronInterface $cron, OutputInterface $output): void {
		try{
			$now = date('Y-m-d h:i:s');
			$output->writeln("[{$now}] Cron[{$cron->getId()}] started.");
			$cron->setUp($this->container);
			$cron->run(new DateTimeImmutable(), $output);
			$now = date('Y-m-d h:i:s');
			$output->writeln("[{$now}] Cron[{$cron->getId()}] finished.");
		}catch (Exception $e){
			$now = date('Y-m-d h:i:s');
			$output->writeln("[{$now}] Cron[{$cron->getId()}] failed with message: {$e->getMessage()}.");
		}
	}



}