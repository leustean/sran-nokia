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
use Psr\Log\LoggerInterface;
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

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	protected function configure(): void {
		$this
			->setName('app:cron:run:due')
			->setDescription('Runs all cron jobs that are due.');
	}

	/**
	 * RunDueCommand constructor.
	 * @param CronRepository     $cronRepository
	 * @param ContainerInterface $container
	 * @param LoggerInterface    $logger
	 */
	public function __construct(CronRepository $cronRepository, ContainerInterface $container, LoggerInterface $logger) {
		$this->cronRepository = $cronRepository;
		$this->container = $container;
		$this->logger= $logger;

		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		foreach ($this->cronRepository->findAll() as $cron) {
			if ($cron->getCronExpresion()->isDue()) {
				$this->runCron($cron);
			}
		}
	}

	/**
	 * @param CronInterface $cron
	 */
	private function runCron(CronInterface $cron): void {
		try{
			$this->logger->notice("Cron[{$cron->getId()}] started.");
			$cron->setUp($this->container);
			$cron->run(new DateTimeImmutable(), $this->logger);
			$this->logger->notice("Cron[{$cron->getId()}] finished.");
		}catch (Exception $e){
			$e = (string)$e;
			$this->logger->error("Cron[{$cron->getId()}] failed {$e}.");
		}
	}



}