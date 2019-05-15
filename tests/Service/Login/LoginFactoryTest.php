<?php


namespace App\Tests\Service\Login;


use App\Service\Login\FakeLogin;
use App\Service\Login\LdapLogin;
use App\Service\Login\LoginFactory;
use App\Tests\AbstractTest;
use ReflectionException;


class LoginFactoryTest extends AbstractTest {

	/**
	 * @throws ReflectionException
	 */
	public function test_getLoginByEnv_givenEnvIsProd(): void {
		$loginFactory = new LoginFactory(
			'prod',
			$this->getMockSession(),
			$this->getMockUserEntityRepository(),
			$this->getMockEntityManager()
		);

		self::assertInstanceOf(LdapLogin::class, $loginFactory->getLogin());
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_getLoginByEnv_givenEnvIsNotProd(): void {
		$loginFactory = new LoginFactory(
			'test',
			$this->getMockSession(),
			$this->getMockUserEntityRepository(),
			$this->getMockEntityManager()
		);

		self::assertInstanceOf(FakeLogin::class, $loginFactory->getLogin());
	}


}