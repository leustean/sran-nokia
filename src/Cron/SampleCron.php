<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-04-02
 * Time: 16:23
 */

namespace App\Cron;


use App\Cron\Prototype\CronInterface;
use Cron\CronExpression;
use DateTimeImmutable;
use Symfony\Component\Console\Output\OutputInterface;

class SampleCron implements CronInterface {

	/**
	 * The name of the cron, should only contain a-z characters and "-"
	 * @return string
	 */
	public function getId(): string {
		return 'sample-cron';
	}

	/**
	 * Example: return Cron\CronExpression::factory('* * * * *');
	 * @return CronExpression
	 */
	public function getCronExpresion(): CronExpression {
		return CronExpression::factory('* * * * *');
	}

	/**
	 * The main method of the cron
	 * @param DateTimeImmutable $now
	 * @param OutputInterface   $output
	 */
	public function run(DateTimeImmutable $now, OutputInterface $output): void {
		$output->writeln($now->format('Y-m-d'));
	}
}