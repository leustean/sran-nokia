<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-03-31
 * Time: 21:34
 */

namespace App\Cron;


use Cron\CronExpression;
use DateTimeImmutable;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface CronInterface {

	/**
	 * Sets the needed dependencies
	 * @param ContainerInterface $container
	 */
	public function setUp(ContainerInterface $container) : void;

	/**
	 * The name of the cron, should only contain a-z characters and "-"
	 * @return string
	 */
	public function getId() : string;

	/**
	 * Example: return Cron\CronExpression::factory('* * * * *');
	 * @return CronExpression
	 */
	public function getCronExpresion() : CronExpression;

	/**
	 * The main method of the cron
	 * @param DateTimeImmutable $now
	 * @param OutputInterface    $output
	 */
	public function run(DateTimeImmutable $now, OutputInterface $output) : void;

}