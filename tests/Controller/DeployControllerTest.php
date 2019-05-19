<?php


namespace App\Tests\Controller;


use App\Tests\AbstractIntegrationTest;
use ReflectionException;

class DeployControllerTest extends AbstractIntegrationTest {

	/**
	 * @throws ReflectionException
	 */
	public function test_showDeployOptions(): void {
		$client = $this->getClient();
		$this->setMockLoginFactory();


		$client->request('GET', '/deploy');

		self::assertSame(200, $client->getResponse()->getStatusCode());
		self::assertCount(11, $client->getCrawler()->filter('input[type="checkbox"]'));
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_performDeploy(): void {
		$client = $this->getClient();
		$client->disableReboot();
		$this->setMockLoginFactory();


		$client->request('POST', '/deploy/perform', [
			'deploy' => [
				'0' => 1,
				'1' => 0
			]
		]);

		self::assertSame(302, $client->getResponse()->getStatusCode());

		$client->followRedirect();
		self::assertSame(200, $client->getResponse()->getStatusCode());
		self::assertCount(1, $client->getCrawler()->filter('input[name="deploy[0]"]'));
		self::assertCount(0, $client->getCrawler()->filter('input[name="deploy[1]"]'));
	}

}