<?php


namespace App\Controller;

use App\Repository\DeviceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller
 * @Route("/")
 */
class MainController extends AbstractController {

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
	public function sbtsAction(DeviceEntityRepository $deviceEntityRepository): Response {
		return $this->render(
			'home/sbts.html.twig',
			[
				'devices' => $deviceEntityRepository->findBy([],['sbtsId' => 'ASC'])
			]
		);
	}

	/**
	 * @Route("/sbts/{sbtsId}", name="main_sbts_details", methods={"GET","POST"}, requirements={"sbtsId"="\d+"})
	 * @param int $sbtsId
	 */
	public function showSbtsAction($sbtsId): void {
		echo $sbtsId;
	}

}