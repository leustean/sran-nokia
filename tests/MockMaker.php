<?php


namespace App\Tests;


use App\Controller\AdminControllerInterface;
use App\Cron\CronInterface;
use App\Repository\CronRepository;
use App\Repository\DeviceEntityRepository;
use App\Repository\SettingsEntityRepository;
use App\Repository\UserEntityRepository;
use App\Service\Login\LoginFactory;
use App\Service\Login\LoginInterface;
use Cron\CronExpression;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @method MockBuilder getMockBuilder(string $className)
 * @method MockObject getMockForAbstractClass($originalClassName, array $arguments = [], $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $mockedMethods = [], $cloneArguments = false)
 **/
trait MockMaker {


	/**
	 * @return LoginFactory|MockObject
	 * @throws ReflectionException
	 */
	public function getMockLoginFactory(): MockObject {
		return $this->getMockBuilder(LoginFactory::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return LoginInterface|MockObject
	 * @throws ReflectionException
	 */
	public function getMockLogin(): MockObject {
		return $this->getMockBuilder(LoginInterface::class)->getMock();
	}

	/**
	 * @return DeviceEntityRepository|MockObject
	 * @throws ReflectionException
	 */
	public function getMockDeviceEntityRepository(): MockObject {
		return $this->getMockBuilder(DeviceEntityRepository::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return UserEntityRepository|MockObject
	 * @throws ReflectionException
	 */
	public function getMockUserEntityRepository(): MockObject {
		return $this->getMockBuilder(UserEntityRepository::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return SettingsEntityRepository|MockObject
	 * @throws ReflectionException
	 */
	public function getMockSettingsEntityRepository(): MockObject {
		return $this->getMockBuilder(SettingsEntityRepository::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return MockObject|CronRepository
	 * @throws ReflectionException
	 */
	public function getMockCronRepository(): MockObject {
		return $this->getMockBuilder(CronRepository::class)->getMock();
	}

	/**
	 * @return MockObject|InputInterface
	 * @throws ReflectionException
	 */
	public function getMockInputInterface(): MockObject {
		return $this->getMockBuilder(InputInterface::class)->getMock();
	}

	/**
	 * @return MockObject|OutputInterface
	 * @throws ReflectionException
	 */
	public function getMockOutputInterface(): MockObject {
		return $this->getMockBuilder(OutputInterface::class)->getMock();
	}

	/**
	 * @return MockObject|CronInterface
	 * @throws ReflectionException
	 */
	public function getMockCronInterface(): MockObject {
		return $this->getMockBuilder(CronInterface::class)->getMock();
	}

	/**
	 * @return MockObject|CronExpression
	 * @throws ReflectionException
	 */
	public function getMockCronExpression(): MockObject {
		return $this->getMockBuilder(CronExpression::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return UrlGeneratorInterface|MockObject
	 * @throws ReflectionException
	 */
	public function getMockUrlGenerator() {
		return $this->getMockBuilder(UrlGenerator::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return FilterControllerEvent|MockObject
	 * @throws ReflectionException
	 */
	public function getMockFilterControllerEvent(): MockObject {
		return $this->getMockBuilder(FilterControllerEvent::class)->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return AdminControllerInterface|MockObject
	 * @throws ReflectionException
	 */
	public function getMockAdminController(): MockObject {
		return $this->getMockBuilder(AdminControllerInterface::class)->getMock();
	}

	/**
	 * @return AdminControllerInterface|MockObject
	 */
	public function getMockController(): MockObject {
		return $this->getMockForAbstractClass(AbstractController::class);
	}

	/**
	 * @return SessionInterface|MockObject
	 * @throws ReflectionException
	 */
	public function getMockSession(): MockObject {
		return $this->getMockBuilder(SessionInterface::class)->getMock();
	}

	/**
	 * @return EntityManagerInterface|MockObject
	 * @throws ReflectionException
	 */
	public function getMockEntityManager(): MockObject {
		return $this->getMockBuilder(EntityManagerInterface::class)->getMock();
	}

	/**
	 * @return Container|MockObject
	 * @throws ReflectionException
	 */
	public function getMockContainer(): MockObject {
		return $this->getMockBuilder(Container::class)->getMock();
	}

	/**
	 * @return LoggerInterface|MockObject
	 * @throws ReflectionException
	 */
	public function getMockLogger(): MockObject {
		return $this->getMockBuilder(LoggerInterface::class)->getMock();
	}

}