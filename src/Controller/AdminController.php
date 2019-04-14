<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", )
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="admin_index", methods={"GET","POST"})
     * @return Response
     */
    public function showDeployOptions(): Response {
        return $this->render(
            'admin/admin.html.twig'

        );
    }
}