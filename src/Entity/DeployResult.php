<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-03-20
 * Time: 22:28
 */

namespace App\Entity;


class DeployResult {

	/**
	 * @var DeployOption
	 */
	private $deployOption;

	/**
	 * @var int
	 */
	private $status;

	/**
	 * @var string
	 */
	private $output;

	/**
	 * DeployResult constructor.
	 * @param DeployOption $deployOption
	 * @param int          $status
	 * @param string       $output
	 */
	public function __construct(DeployOption $deployOption, int $status, string $output) {
		$this->deployOption = $deployOption;
		$this->status = $status;
		$this->output = $output;
	}


	/**
	 * @return DeployOption
	 */
	public function getDeployOption(): DeployOption {
		return $this->deployOption;
	}

	/**
	 * @return bool
	 */
	public function isSuccessful(): bool {
		return $this->status === 0;
	}


	/**
	 * @return string
	 */
	public function getOutput(): string {
		return $this->output;
	}

}