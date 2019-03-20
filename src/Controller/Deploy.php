<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-03-19
 * Time: 17:28
 */

namespace App\Controller;


use App\Entity\DeployResult;
use App\Repository\DeployOptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deploy", )
 */
class Deploy extends AbstractController {

	private $currentWorkingDirectory;

	private $commandRunDir;

	public function __construct($commandRunDir) {
		$this->commandRunDir = $commandRunDir;
		$this->currentWorkingDirectory = getcwd();
	}

	/**
	 * @Route("/", name="deploy_options_list", methods={"GET","POST"})
	 * @param DeployOptionRepository $deployOptionRepository
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showDeployOptions(DeployOptionRepository $deployOptionRepository): \Symfony\Component\HttpFoundation\Response {
		return $this->render(
			'deploy/deploy-options.html.twig',
			[
				'options' => $deployOptionRepository->findAll()
			]
		);
	}

	/**
	 * @Route("/perform", name="deploy_option_perform", methods={"GET","POST"})
	 * @param Request                $request
	 * @param DeployOptionRepository $deployOptionRepository
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function performDeploy(Request $request, DeployOptionRepository $deployOptionRepository): \Symfony\Component\HttpFoundation\Response {

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
				$deployResult[] = new DeployResult($deployOption, $status, implode($output,PHP_EOL));
				$status = 1;
				$output = [];
			}
		}
		$this->restoreWorkingDirectory();

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