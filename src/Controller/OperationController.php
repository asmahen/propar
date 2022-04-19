<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class OperationController extends AbstractController
{
    /**
     *@isGranted("ROLE_USER", message="Vous n'avez pas accès à cette session")
     * @Route("/operation", name="app_operation")
     */
    public function index(): Response
    {
        return $this->render('operation/index.html.twig', [
            'controller_name' => 'OperationController',
        ]);
    }
}
