<?php


namespace App\Tests\Controller;


use App\Controller\LogController;
use App\Tests\AbstractIntegrationTest;
use DateTimeImmutable;
use ReflectionException;

class LogControllerTest extends AbstractIntegrationTest {

	/**
	 * @var string
	 */
	protected static $appDir = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'log-controller' . DIRECTORY_SEPARATOR;

	/**
	 * @var string
	 */
	protected static $logDir;

	/**
	 * @var string
	 */
	protected static $logFile;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		self::$logDir = self::$appDir . 'var' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR;
		self::$logFile = self::$logDir . 'app-' . (new DateTimeImmutable())->format('Y-m-d') . '.log';
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_indexAction_givenThereIsALogFile(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();

		$content = 'log-controller-test-content';
		file_put_contents(self::$logFile, $content);

		$controller = new LogController(self::$appDir);
		$this->setService(LogController::class, $controller);

		$client->request('GET', '/log');

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		$crawler = $client->getCrawler();
		$pageText = $crawler->filter('pre')->text();
		self::assertSame($content, $pageText);

		if (file_exists(self::$logFile)) {
			unlink(self::$logFile);
		}
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_indexAction_givenThereIsNoLogFile(): void {
		$this->setMockLoginFactory();
		$client = $this->getClient();

		$controller = new LogController(self::$appDir);
		$this->setService(LogController::class, $controller);

		$client->request('GET', '/log');

		$response = $client->getResponse();
		self::assertEquals(200, $response->getStatusCode());
		$crawler = $client->getCrawler();
		$pageText = $crawler->filter('pre')->text();
		self::assertSame('', $pageText);
	}

}