<?php

namespace App\Controller;

use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @Route("/users", name="admin_users", methods={"GET","POST"})
     * @return Response
     */
    public function usersAction(UserEntityRepository $userEntityRepository, Request $request, EntityManagerInterface $entityManager): Response {
        if($this->shouldRedirectUser()){
            return $this->redirectToCorrectPage();
        }
        foreach ($request->get("normalUsers",[]) as $user){
            $dbUser = $userEntityRepository->find($user);
            $dbUser->setIsAdmin(true);
            $entityManager->persist($dbUser);
        }
        foreach ($request->get("adminUsers",[]) as $user){
            $dbUser = $userEntityRepository->find($user);
            $dbUser->setIsAdmin(false);
            $entityManager->persist($dbUser);
        }
        $entityManager->flush();
        return $this->render(
            'admin/users.html.twig',
            ["normalUsers" => $userEntityRepository->findBy(["isAdmin"=>0]),
                "adminUsers" => $userEntityRepository->findBy(["isAdmin"=>1])
        ]

        );
    }
}