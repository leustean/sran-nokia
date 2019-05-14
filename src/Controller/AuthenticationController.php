<?php


namespace App\Controller;

use App\Form\LoginType;
use App\Service\Login\LoginException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LoginController
 * @package App\Controller
 * @Route("/")
 */
class AuthenticationController extends AbstractAppController {

	/**
	 * @Route("/login", name="login_index", methods={"GET", "POST"})
	 * @param Request      $request
	 * @return Response
	 */
	public function loginAction(Request $request): Response {
		if($this->login->isLogged()){
			return $this->getRedirect();
		}

		$form = $this->createForm(LoginType::class, null, ['attr' => ['class' => 'login__form form']]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$this->login->authenticate($form->getData());
				return $this->getRedirect();
			} catch (LoginException $e) {
				$form->addError(new FormError('Invalid credentials'));
			}
		}

		return $this->render(
			'login/login.html.twig',
			[
				'form' => $form->createView(),
			]
		);
	}

	/**
	 * @Route("/logout", name="logout_index", methods={"GET"})
	 * @return Response
	 */
	public function logoutAction(): Response {
		$this->login->logOut();
		return $this->redirectToRoute('login_index');
	}

	/**
	 * @return Response
	 */
	private function getRedirect(): Response {
		if($this->login->isAdmin()){
			return $this->redirectToRoute('admin_index');
		}
		return $this->redirectToRoute('main_index');
	}

}