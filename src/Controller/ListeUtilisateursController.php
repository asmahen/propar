<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeUtilisateursController extends AbstractController
{
    /**
     * @Route("/liste/utilisateurs", name="app_liste_utilisateurs")
     */
    public function index(): Response
    {
        return $this->render('liste_utilisateurs/index.html.twig', [
            'controller_name' => 'ListeUtilisateursController',
        ]);
    }
}
