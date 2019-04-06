<?php


namespace App\Cron\Prototype;

use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CronCommand extends Command {

	public function runCron(CronInterface $cron, OutputInterface $output): void {
		try{
			$now = date('Y-m-d h:i:s');
			$output->writeln("[{$now}] Cron[{$cron->getId()}] started.");
			$cron->run(new DateTimeImmutable(), $output);
			$now = date('Y-m-d h:i:s');
			$output->writeln("[{$now}] Cron[{$cron->getId()}] finished.");
		}catch (Exception $e){
			$now = date('Y-m-d h:i:s');
			$output->writeln("[{$now}] Cron[{$cron->getId()}] failed with message: {$e->getMessage()}.");
		}
	}

}