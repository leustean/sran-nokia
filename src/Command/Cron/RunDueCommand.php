<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-04-02
 * Time: 15:54
 */

namespace App\Command\Cron;


use App\Cron\Prototype\CronCommand;
use App\Repository\CronRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunDueCommand extends CronCommand {

	/**
	 * @var CronRepository
	 */
	private $cronRepository;

	protected function configure(): void {
		$this
			->setName('app:cron:run:due')
			->setDescription('Runs all cron jobs that are due.');

	}

	/**
	 * RunDueCommand constructor.
	 * @param CronRepository $cronRepository
	 */
	public function __construct(CronRepository $cronRepository) {
		$this->cronRepository = $cronRepository;

		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		foreach ($this->cronRepository->findAll() as $cron) {
			if ($cron->getCronExpresion()->isDue()) {
				$this->runCron($cron, $output);
			}
		}
	}

}