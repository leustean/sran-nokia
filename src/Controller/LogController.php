<?php


namespace App\Controller;


use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController implements AdminControllerInterface {

	/**
	 * @var string
	 */
	protected $logDir;

	public function __construct(string $appDir) {
		$this->logDir = $appDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR;
	}

	/**
	 * @Route("/log", name="log_index", methods={"GET","POST"})
	 * @return Response
	 * @throws Exception
	 */
	public function indexAction(): Response {
		$now = new DateTimeImmutable();

		$appLogPath = $this->logDir . 'app-' . $now->format('Y-m-d') . '.log';
		$appLog = file_exists($appLogPath) ? file_get_contents($appLogPath) : '';

		return $this->render(
			'log/log.html.twig',
			[
				'appLog' => $appLog
			]
		);
	}
}