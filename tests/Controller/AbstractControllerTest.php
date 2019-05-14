<?php


namespace App\Tests\Controller;

use App\Entity\UserEntity;
use App\Repository\DeviceEntityRepository;
use App\Repository\SettingsEntityRepository;
use App\Service\Login\LoginFactory;
use App\Service\Login\LoginInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractControllerTest extends WebTestCase {

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
	 * @return ObjectManager|object
	 */
	protected function getEntityManager(){
		return $this->entityManager;
	}

	/**
	 * @return LoginFactory|MockObject
	 * @throws ReflectionException
	 */
	protected function getMockLoginFactory(): MockObject {
		return $this->getMockBuilder(LoginFactory::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return LoginInterface|MockObject
	 * @throws ReflectionException
	 */
	protected function getMockLogin(): MockObject {
		return $this->getMockBuilder(LoginInterface::class)->getMock();
	}

	/**
	 * @return DeviceEntityRepository|MockObject
	 * @throws ReflectionException
	 */
	protected function getMockDeviceEntityRepository(): MockObject {
		return $this->getMockBuilder(DeviceEntityRepository::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return SettingsEntityRepository|MockObject
	 * @throws ReflectionException
	 */
	protected function getMockSettingsEnityRepository(): MockObject {
		return $this->getMockBuilder(SettingsEntityRepository::class)->disableOriginalConstructor()->getMock();
	}

}