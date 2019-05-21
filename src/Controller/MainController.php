<?php


namespace App\Controller;

use App\Form\DeviceType;
use App\Repository\DeviceEntityRepository;
use App\Repository\SearchFieldsRepository;
use App\Service\Login\LoginFactory;
use App\Service\Login\LoginInterface;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller
 * @Route("/")
 */
class MainController extends AbstractController {

	/**
	 * @var LoginInterface
	 */
	protected $login;


	public function __construct(LoginFactory $loginFactory) {
		$this->login = $loginFactory->getLogin();
	}

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
	 * @Route("/sbts", name="main_sbts", methods={"GET","POST"})
	 * @param DeviceEntityRepository $deviceEntityRepository
	 * @param SearchFieldsRepository $searchFieldsRepository
	 * @return Response
	 * @throws DBALException
	 */
	public function sbtsAction(DeviceEntityRepository $deviceEntityRepository, SearchFieldsRepository $searchFieldsRepository, Request $request): Response {
		$tables = $searchFieldsRepository->getTables();

		$fieldsToSearchBy = [];

		foreach ($tables as $alias => $table){
			$requestTable = $request->get($alias, []);
			foreach ($requestTable as $requestField => $requestValue){
				if($requestValue !== ''){
					$fieldsToSearchBy[$alias]['fields'][$requestField] = $requestValue;
					$tables[$alias]['selected'][$requestField] = $requestValue;
				}
			}
			if(!empty($fieldsToSearchBy[$alias]['fields'])){
				$fieldsToSearchBy[$alias]['table'] = $table['tableName'];
				if(!empty($table['tableCondition'])){
					$fieldsToSearchBy[$alias]['condition'] = $table['tableCondition'];
				}
			}
		}

		return $this->render(
			'home/sbts.html.twig',
			[
				'devices' => $deviceEntityRepository->findSearchResult($fieldsToSearchBy),
				'tables' => $tables,
				'showFiltersTab' => count($fieldsToSearchBy) !== 0
			]
		);
	}

	/**
	 * @Route("/sbts/{sbtsId}", name="main_sbts_details", methods={"GET","POST"}, requirements={"sbtsId"="\d+"})
	 * @param int                    $sbtsId
	 * @param DeviceEntityRepository $deviceEntityRepository
	 * @param Request                $request
	 * @param EntityManagerInterface $entityManager
	 * @param SessionInterface       $session
	 * @return Response
	 * @throws NonUniqueResultException
	 */
	public function showSbtsAction($sbtsId, DeviceEntityRepository $deviceEntityRepository, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response {
		$deviceEntity = $deviceEntityRepository->getBySbtsId($sbtsId);

		if ($deviceEntity === null) {
			$this->addFlash('notice', "SBTS with id [{$sbtsId}] does not exist");
			return $this->redirectToRoute('main_sbts');
		}

		$showSettingsTab = false;
		$loggedUser = $this->login->getPrincipal();
		if ($loggedUser !== null) {
			if ($loggedUser->getIsAdmin() || $loggedUser->getEmail() === $deviceEntity->getSbtsOwner()) {
				$showSettingsTab = true;
			}
		}

		$message = null;
		$form = $this->createForm(DeviceType::class, $deviceEntity);
		if($showSettingsTab){
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->persist($form->getData());
				$entityManager->flush();
				$session->set('message', 'The device has been modified successfully');
				return $this->redirect($this->generateUrl('main_sbts_details', ['sbtsId' => $deviceEntity->getSbtsId()]) . '#settings');
			}

			if ($session->has('message')) {
				$message = $session->get('message');
				$session->remove('message');
			}
		}


		return $this->render(
			'home/sbts-detail.html.twig',
			[
				'device' => $deviceEntity,
				'showSettingsTab' => $showSettingsTab,
				'form' => $form->createView(),
				'message' => $message
			]
		);
	}

}