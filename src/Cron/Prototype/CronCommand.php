<?php


namespace App\Cron\Prototype;

use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CronCommand extends Command {

	protected function runCron(CronInterface $cron, OutputInterface $output): void {
		$now = new DateTimeImmutable();
		$output->writeln("[{$now->format('Y-m-d h:i:s')}] Cron[{$cron->getId()}] started.");
		$cron->run($now, $output);
		$output->writeln("[{$now->format('Y-m-d h:i:s')}] Cron[{$cron->getId()}] finished.");
	}

}