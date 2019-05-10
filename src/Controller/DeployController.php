<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-03-19
 * Time: 17:28
 */

namespace App\Controller;


use App\Entity\DeployResultEntity;
use App\Service\Login\LoginFactory;
use App\Repository\DeployOptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deploy", )
 */
class DeployController extends AbstractAppController {

	private const DEPLOY_RESULT = 'DEPLOY_CONTROLLER_DEPLOY_RESULT';

	private $currentWorkingDirectory;

	private $commandRunDir;

	public function __construct(LoginFactory $loginFactory, $commandRunDir) {
		parent::__construct($loginFactory);
		$this->commandRunDir = $commandRunDir;
		$this->currentWorkingDirectory = getcwd();
	}

	/**
	 * @Route("/", name="deploy_options_list", methods={"GET","POST"})
	 * @param DeployOptionRepository $deployOptionRepository
	 * @return Response
	 */
	public function showDeployOptions(DeployOptionRepository $deployOptionRepository): Response {
		if($this->shouldRedirectUser()){
			return $this->redirectToCorrectPage();
		}

		return $this->render(
			'deploy/deploy-options.html.twig',
			[
				'options' => $deployOptionRepository->findAll()
			]
		);
	}

	/**
	 * @Route("/perform", name="deploy_option_perform", methods={"POST"})
	 * @param Session                $session
	 * @param Request                $request
	 * @param DeployOptionRepository $deployOptionRepository
	 * @return Response
	 */
	public function performDeploy(Session $session, Request $request, DeployOptionRepository $deployOptionRepository): Response {
		if($this->shouldRedirectUser()){
			return $this->redirectToCorrectPage();
		}

		$deployResult = [];
		$this->setRunDirectory();

		$status = 1;
		$output = [];
		foreach ($request->request->keys() as $option) {
			$deployOption = $deployOptionRepository->find($option);
			if ($deployOption !== null) {
				exec(
					$deployOption->getCommand(),
					$output,
					$status);
				$deployResult[] = new DeployResultEntity($deployOption, $status, implode($output,PHP_EOL));
				$status = 1;
				$output = [];
			}
		}
		$this->restoreWorkingDirectory();
		$session->set(self::DEPLOY_RESULT, $deployResult);

		return $this->redirectToRoute('deploy_option_result');
	}

	/**
	 * @Route("/result", name="deploy_option_result", methods={"GET"})
	 * @param Session $session
	 * @return Response
	 */
	public function showDeployResult(Session $session): Response {
		if(!$this->login->isAdmin()){
			return $this->redirectToRoute('login_index');
		}

		$deployResult = $session->get(self::DEPLOY_RESULT, []);
		return $this->render(
			'deploy/deploy-result.html.twig',
			[
				'deployResult' => $deployResult
			]
		);
	}


	private function setRunDirectory(): void {
		chdir($this->commandRunDir);
	}

	private function restoreWorkingDirectory(): void {
		chdir($this->currentWorkingDirectory);
	}

}