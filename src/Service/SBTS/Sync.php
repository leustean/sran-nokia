<?php


namespace App\Service\SBTS;


use App\Entity\DeviceEntity;

class Sync implements SyncInterface {

	/**
	 * @var string
	 */
	private $commandToExecute;

	/**
	 * @var array
	 */
	private $commandAttributes;

	/**
	 * @var array
	 */
	private $proceduresToRun;

	/**
	 * @var string
	 */
	private const executableName = 'get-info';

	/**
	 * @var string
	 */
	private const executablePath = __DIR__ . '../../../bin/admin-cli/';

	/**
	 * @var string
	 */
	private const outputFileName = '.output.json';

	/**
	 * @var string
	 */
	private const debugFileName = '.debug.txt';

	/**
	 * Sync constructor.
	 */
	public function __construct() {
		if (stripos(PHP_OS, 'WIN') === 0) {
			$this->commandToExecute = self::executablePath . self::executableName . '.bat';
		} else {
			$this->commandToExecute = 'sh ' . self::executablePath . self::executableName . '.sh';
		}

		$this->commandAttributes = [
			'bts-username' => '',
			'bts-password' => '',
			'bts-host' => '',
			'bts-port' => '3600',
			'data' => '{}',
			'format' => 'human',
			'output-json' => self::executablePath . self::outputFileName,
			'debug' => self::executablePath . self::debugFileName
		];

		$this->proceduresToRun = ['getBtsInformation', 'getSynchronizationInformation', 'getActiveAlarms'];
	}

	/**
	 * @return array
	 */
	public function getProcedures(): array {
		return $this->proceduresToRun;
	}

	/**
	 * @param DeviceEntity $deviceEntity
	 * @return $this
	 */
	public function setDevice(DeviceEntity $deviceEntity): SyncInterface {
		$this->commandAttributes['bts-host'] = $deviceEntity->getIp();
		$this->commandAttributes['bts-username'] = $deviceEntity->getUser();
		$this->commandAttributes['bts-password'] = $deviceEntity->getPassword();
		$this->commandAttributes['bts-port'] = $deviceEntity->getPort();

		return $this;
	}

	/**
	 * @return $this
	 */
	private function setSetFileNames(): self {
		$requestId = uniqid('sbts', true);
		$this->commandAttributes['output-json'] = self::executablePath . $requestId . self::outputFileName;
		$this->commandAttributes['debug'] = self::executablePath . $requestId . self::debugFileName;

		return $this;
	}

	/**
	 * @return string
	 */
	protected function getOutputFileName(): string {
		return $this->commandAttributes['output-json'];
	}

	/**
	 * @return string
	 */
	protected function getDebugFileName(): string {
		return $this->commandAttributes['debug'];
	}

	/**
	 * @param array $payload
	 * @return $this
	 */
	private function setPayload(array $payload): self {
		$this->commandAttributes['data'] = json_encode($payload);

		return $this;
	}

	/**
	 * @param String $procedureName
	 * @return $this
	 */
	public function addProcedure(String $procedureName): self {
		$this->proceduresToRun[] = $procedureName;

		return $this;
	}

	/**
	 * @return string
	 */
	private function getCommand(): string {
		$commandToExecute = $this->commandToExecute;

		foreach ($this->commandAttributes as $commandAttribute => $attributeValue) {
			$commandToExecute .= ' --' . $commandAttribute . '="' . $attributeValue . '"';
		}

		return $commandToExecute;
	}

	/**
	 * @return array
	 * @throws SyncException
	 */
	public function fetchData(): array {
		$fetchedData = [];
		foreach ($this->proceduresToRun as $procedure) {
			exec($this
				->setPayLoad([
				'requestId' => 1,
				'parameters' => [
					'name' => $procedure
				]
			])
				->setSetFileNames()
				->getCommand()
			);
			$fullPathOutput = self::executablePath . self::outputFileName;

			if (!file_exists($fullPathOutput)) {
				throw new SyncException("Can't find output file");
			}

			$fetchedData[$procedure] = json_decode(file_get_contents($fullPathOutput), false);

			if($fetchedData[$procedure] === null || json_last_error() !== JSON_ERROR_NONE){
				throw new SyncException('Error parsing output file');
			}
		}

		return $fetchedData;
	}
}