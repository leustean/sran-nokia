<?php

namespace App\Repository;

use App\Cron\CronInterface;
use App\Cron\SampleCron;

class CronRepository {

	private $installedCronJobs = [];

	public function __construct() {
		$this->setUpCronJobs();
	}

	/**
	 * @return CronInterface[]
	 */
	public function findAll(): array {
		return $this->installedCronJobs;
	}

	/**
	 * @param $id
	 * @return CronInterface|null
	 */
	public function find($id): ?CronInterface {
		return $this->installedCronJobs[$id] ?? null;
	}

	private function setUpCronJobs(): void {
		$this->installCronJob(new SampleCron());
	}

	/**
	 * @param CronInterface $cron
	 * @return CronRepository
	 */
	private function installCronJob(CronInterface $cron) : self {
		$this->installedCronJobs[$cron->getId()] = $cron;

		return $this;
	}

}
