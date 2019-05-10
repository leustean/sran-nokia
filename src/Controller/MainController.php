<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller
 * @Route("/")
 */
class MainController extends AbstractAppController {

	/**
	 * @Route("/", name="main_index", methods={"GET"})
	 * @return Response
	 */
	public function indexAction(): Response {
		return $this->render(
			'home/home.html.twig'
		);
	}

}