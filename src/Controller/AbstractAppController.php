<?php


namespace App\Controller;


use App\Service\Login\LoginFactory;
use App\Service\Login\LoginInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAppController extends AbstractController {

	/**
	 * @var LoginInterface
	 */
	protected $login;

	/**
	 * @var Response
	 */
	private $response;

	public function __construct(LoginFactory $loginFactory) {
		$this->login = $loginFactory->getLogin();
	}

	protected function shouldRedirectUser(): bool {
		if(!$this->login->isLogged()){
			$this->response = $this->redirectToRoute('login_index');
			return true;
		}
		if(!$this->login->isAdmin()){
			$this->response = $this->redirectToRoute('main_index');
			return true;
		}
		return false;
	}

	protected function redirectToCorrectPage(): Response {
		return $this->response;
	}
}