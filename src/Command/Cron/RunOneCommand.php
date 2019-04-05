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
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunOneCommand extends CronCommand {

	/**
	 * @var CronRepository
	 */
	private $cronRepository;

	protected function configure() : void {
		$this
			->setName('app:cron:run:one')
			->setDescription('Runs the cron jobs with the given name.')
			->addArgument('cron', InputArgument::REQUIRED, 'The id of the cron to run');

	}

	/**
	 * RunDueCommand constructor.
	 * @param CronRepository         $cronRepository
	 */
	public function __construct(CronRepository $cronRepository) {
		$this->cronRepository = $cronRepository;
		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$cron = $this->cronRepository->find($input->getArgument('cron'));
		if($cron === null){
			throw new RuntimeException('Cron not found');
		}
		$this->runCron($cron, $output);
	}

}