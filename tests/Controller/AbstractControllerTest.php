<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractControllerTest extends WebTestCase {

	/**
	 * @return Client
	 */
	protected function getClient(): Client {
		return self::createClient();
	}

	/**
	 * @return ContainerInterface
	 */
	protected function getContainer(): ContainerInterface {
		return self::$container;
	}

}