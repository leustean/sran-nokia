<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", )
 */
class AdminController extends AbstractAppController {

	/**
	 * @Route("/", name="admin_index", methods={"GET","POST"})
	 * @return Response
	 */
	public function indexAction(): Response {
		if($this->shouldRedirectUser()){
			return $this->redirectToCorrectPage();
		}

		return $this->render(
			'admin/admin.html.twig'
		);
	}
}