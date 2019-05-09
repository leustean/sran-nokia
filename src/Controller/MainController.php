<?php


namespace App\Controller;

use App\Repository\DeviceEntityRepository;
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

	/**
	 * @Route("/sbts", name="main_sbts", methods={"GET"})
	 * @param DeviceEntityRepository $deviceEntityRepository
	 * @return Response
	 */
	public function showDevicesAction(DeviceEntityRepository $deviceEntityRepository): Response {
		return $this->render(
			'home/sbts.html.twig',
			[
				'devices' => $deviceEntityRepository->findBy([],['sbtsId' => 'ASC'])
			]
		);
	}

}