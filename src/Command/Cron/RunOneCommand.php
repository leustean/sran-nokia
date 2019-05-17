<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-04-02
 * Time: 15:54
 */

namespace App\Command\Cron;

use App\Repository\CronRepository;
use DateTimeImmutable;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RunOneCommand extends Command {

	/**
	 * @var CronRepository
	 */
	protected $cronRepository;

	/**
	 * @var ContainerInterface
	 */
	protected $container;

	protected function configure() : void {
		$this
			->setName('app:cron:run:one')
			->setDescription('Runs the cron jobs with the given name.')
			->addArgument('cron', InputArgument::REQUIRED, 'The id of the cron to run')
			->addArgument('timestamp', InputArgument::OPTIONAL, 'The timestamp to pass to the cron');
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

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @return int|void|null
	 * @throws Exception
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$cron = $this->cronRepository->find($input->getArgument('cron'));
		if($cron === null){
			throw new RuntimeException('Cron not found');
		}
		$timestamp = $input->getArgument('timestamp');
		$timestamp = new DateTimeImmutable($timestamp ?: 'now');

		$cron->setUp($this->container);
		$cron->run($timestamp, $output);
	}

}