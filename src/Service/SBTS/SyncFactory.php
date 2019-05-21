<?php


namespace App\Service\SBTS;


class SyncFactory {

	/**
	 * @var string
	 */
	private const PROD_ENV = 'prod';

	/**
	 * @var SyncInterface
	 */
	protected $sync;

	public function __construct($env) {
		if ($env === self::PROD_ENV) {
			$this->sync = new Sync();
		} else {
			$this->sync = new FakeSync();
		}
	}

	public function getSync(): SyncInterface {
		return $this->sync;
	}

}