<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("/operation", name="app_operation")
     */
    public function index(): Response
    {
        return $this->render('operation/index.html.twig', [
            'controller_name' => 'OperationController',
        ]);
    }
}
