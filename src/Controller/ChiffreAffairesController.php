<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChiffreAffairesController extends AbstractController
{
    /**
     * @Route("/chiffre/affaires", name="app_chiffre_affaires")
     */
    public function index(): Response
    {
        return $this->render('chiffre_affaires/index.html.twig', [
            'controller_name' => 'ChiffreAffairesController',
        ]);
    }
}
