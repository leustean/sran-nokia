<?php


namespace App\Tests\EventSubscriber;

use App\EventSubscriber\SecuritySubscriber;
use App\Tests\AbstractTest;
use ReflectionException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecuritySubscriberTest extends AbstractTest {

	/**
	 * @throws ReflectionException
	 */
	public function test_adminsAreAllowedOnAdminPage(): void {
		$loginFactory = $this->getMockLoginFactory();
		$urlGenerator = $this->getMockUrlGenerator();
		$event = $this->getMockFilterControllerEvent();
		$adminController = $this->getMockAdminController();

		$loginService = $this->getMockLogin();

		$loginFactory
			->method('getLogin')
			->willReturn($loginService);

		$loginService
			->expects($this->once())
			->method('isLogged')
			->willReturn(true);
		$loginService
			->expects($this->once())
			->method('isAdmin')
			->willReturn(true);

		$event
			->method('getController')
			->willReturn([$adminController]);
		$event
			->expects($this->never())
			->method('setController');

		$securitySubscriber = new SecuritySubscriber($loginFactory, $urlGenerator);

		$securitySubscriber->denyAccessToAdminSection($event);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_normalUsersAreNotAllowedOnAdminPage(): void {
		$loginFactory = $this->getMockLoginFactory();
		$urlGenerator = $this->getMockUrlGenerator();
		$event = $this->getMockFilterControllerEvent();
		$adminController = $this->getMockAdminController();

		$loginService = $this->getMockLogin();

		$loginFactory
			->method('getLogin')
			->willReturn($loginService);
		$loginService
			->method('isLogged')
			->willReturn(true);
		$loginService
			->method('isAdmin')
			->willReturn(false);

		$event
			->method('getController')
			->willReturn([$adminController]);
		$event
			->expects($this->once())
			->method('setController')
			->with($this->callback(static function (callable $callback) {
				/**
				 * @var RedirectResponse $result
				 */
				$result = $callback();
				self::assertSame($result->getStatusCode(), 302);
				self::assertSame($result->getTargetUrl(), 'main_url');
				return true;
			}));

		$urlGenerator
			->expects($this->once())
			->method('generate')
			->with($this->equalTo('main_index'))
			->willReturn('main_url');

		$securitySubscriber = new SecuritySubscriber($loginFactory, $urlGenerator);

		$securitySubscriber->denyAccessToAdminSection($event);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_anonymousUsersAreNotAllowedOnAdminPage(): void {
		$loginFactory = $this->getMockLoginFactory();
		$urlGenerator = $this->getMockUrlGenerator();
		$event = $this->getMockFilterControllerEvent();
		$adminController = $this->getMockAdminController();

		$loginService = $this->getMockLogin();

		$loginFactory
			->method('getLogin')
			->willReturn($loginService);
		$loginService
			->method('isLogged')
			->willReturn(false);
		$loginService
			->method('isAdmin')
			->willReturn(false);

		$event
			->method('getController')
			->willReturn([$adminController]);
		$event
			->expects($this->once())
			->method('setController')
			->with($this->callback(static function (callable $callback) {
				/**
				 * @var RedirectResponse $result
				 */
				$result = $callback();
				self::assertSame($result->getStatusCode(), 302);
				self::assertSame($result->getTargetUrl(), 'login_url');
				return true;
			}));

		$urlGenerator
			->expects($this->once())
			->method('generate')
			->with($this->equalTo('login_index'))
			->willReturn('login_url');

		$securitySubscriber = new SecuritySubscriber($loginFactory, $urlGenerator);

		$securitySubscriber->denyAccessToAdminSection($event);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_onlyAdminPagesAreChecked(): void {
		$loginFactory = $this->getMockLoginFactory();
		$urlGenerator = $this->getMockUrlGenerator();
		$event = $this->getMockFilterControllerEvent();
		$controller = $this->getMockController();

		$loginService = $this->getMockLogin();

		$loginFactory
			->method('getLogin')
			->willReturn($loginService);

		$loginService
			->expects($this->never())
			->method('isLogged');
		$loginService
			->expects($this->never())
			->method('isAdmin');

		$event
			->method('getController')
			->willReturn([$controller]);
		$event
			->expects($this->never())
			->method('setController');



		$securitySubscriber = new SecuritySubscriber($loginFactory, $urlGenerator);

		$securitySubscriber->denyAccessToAdminSection($event);
	}

}