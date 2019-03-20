<?php

namespace App\Entity;


class DeployOption {

	private $id;

	private $name;

	private $command;

	/**
	 * DeployOption constructor.
	 * @param int    $id
	 * @param string $name
	 * @param string $command
	 */
	public function __construct(int $id, string $name, string $command) {
		$this->id = $id;
		$this->name = $name;
		$this->command = $command;
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getName(): ?string {
		return $this->name;
	}


	public function getCommand(): ?string {
		return $this->command;
	}

}
