<?php


namespace App\Tests\Controller;

use App\Entity\UserEntity;
use App\Service\Login\LoginFactory;
use App\Tests\MockMaker;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractControllerTest extends WebTestCase {
	use MockMaker;

	/**
	 * @var Client
	 */
	protected $client;

	/** @var  Application $application */
	protected static $application;


	/** @var  EntityManager $entityManager */
	protected $entityManager;

	/**
	 * @throws Exception
	 */
	public function setUp(): void {
		self::runCommand('doctrine:database:drop --force');
		self::runCommand('doctrine:database:create');
		self::runCommand('doctrine:schema:create');

		$this->client = static::createClient();
		self::$container = $this->client->getContainer();
		$this->entityManager = self::$container->get('doctrine.orm.entity_manager');

		parent::setUp();
	}

	/**
	 * @param $command
	 * @return int
	 * @throws Exception
	 */
	protected static function runCommand($command): int {
		$command = sprintf('%s --quiet', $command);

		return self::getApplication()->run(new StringInput($command));
	}

	protected static function getApplication(): Application {
		if (null === self::$application) {
			$client = static::createClient();

			self::$application = new Application($client->getKernel());
			self::$application->setAutoExit(false);
		}

		return self::$application;
	}

	/**
	 * @throws Exception
	 */
	protected function tearDown(): void {
		self::runCommand('doctrine:database:drop --force');

		parent::tearDown();

		$this->entityManager->close();
		$this->entityManager = null;
	}

	/**
	 * @return Client
	 */
	protected function getClient(): Client {
		return $this->client;
	}

	/**
	 * @return ContainerInterface
	 */
	protected function getContainer(): ContainerInterface {
		return self::$container;
	}

	/**
	 * @throws ReflectionException
	 */
	protected function setMockLoginFactory(): void {
		$loginFactory = $this->getMockLoginFactory();
		$loginService = $this->getMockLogin();

		$loginFactory->method('getLogin')->willReturn($loginService);
		$loginService->method('isLogged')->willReturn(true);
		$loginService->method('isAdmin')->willReturn(true);

		$userEntity = new UserEntity();
		$userEntity->setEmail('test@test.com');
		$userEntity->setIsAdmin(true);
		$loginService->method('getPrincipal')->willReturn($userEntity);

		$this->setService(LoginFactory::class, $loginFactory);
	}


	/**
	 * @param String $serviceName
	 * @param        $serviceInstance
	 */
	protected function setService(String $serviceName, $serviceInstance): void {
		self::$container->set('test.' . $serviceName, $serviceInstance);
	}

	/**
	 * @param String $serviceName
	 * @return object
	 */
	protected function getService(String $serviceName) {
		return self::$container->get('test.' . $serviceName);
	}

	/**
	 * @return object|Session
	 */
	protected function getSession(){
		return self::$container->get('session');
	}

	/**
	 * @return EntityManagerInterface|ObjectManager|object
	 */
	protected function getEntityManager() {
		return $this->entityManager;
	}
	
}