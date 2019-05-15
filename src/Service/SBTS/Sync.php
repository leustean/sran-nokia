<?php


namespace App\Service\SBTS;


use App\Entity\DeviceEntity;

class Sync {
    private $executableExtension;

    private $commandToExecute;

    private $commandAttributes;

    private $proceduresToRun;

    private const executableName = 'get-info';

    private const executablePath = __DIR__ . '../../../bin/admin-cli/';

    private const outputFileName = 'output.json';

    private const debugFileName = 'debug.txt';

    public function __construct() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->executableExtension = '.bat';
            $this->commandToExecute = self::executablePath . self::executableName . $this->executableExtension;
        } else {
            $this->executableExtension = '.sh';
            $this->commandToExecute = 'sh ' . self::executablePath . self::executableName . $this->executableExtension;
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

    public function setDevice(DeviceEntity $deviceEntity) {
        $this->commandAttributes['bts-host'] = $deviceEntity->getIp();
        $this->commandAttributes['bts-username'] = $deviceEntity->getUser();
        $this->commandAttributes['bts-password'] = $deviceEntity->getPassword();
        $this->commandAttributes['bts-port'] = $deviceEntity->getPort();
    }

    private function setPayload(array $payload) {
        $this->commandAttributes['data'] = json_encode($payload);
    }

    public function addProcedure(String $procedureName) : string {
        $this->proceduresToRun[] = $procedureName;
    }

    private function injectCommandAttributes() {
        $commandToExecute = $this->commandToExecute;

        foreach($this->commandAttributes as $commandAttribute => $attributeValue) {
            $commandToExecute .=  ' --' . $commandAttribute . '="' . $attributeValue . '"';
        }

        return $commandToExecute;
    }

    public function fetchData() : array {
        $fetchedData = [];
        foreach ($this->proceduresToRun as $procedure) {
            $this->setPayLoad([
                'requestId' => 1,
                'parameters' => [
                    'name' => $procedure
                ]
            ]);

            exec($this->injectCommandAttributes());
            $fullPathOutput = self::executablePath . self::outputFileName;

            if (file_exists($fullPathOutput)) {
                $fetchedData[$procedure] = json_decode(file_get_contents($fullPathOutput), true);
            }
        }

        return $fetchedData;
    }
}