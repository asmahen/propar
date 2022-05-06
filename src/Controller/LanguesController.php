<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LanguesController extends AbstractController
{
    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request): Response
    {
        // On stocke la langue demandée dans la session de
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
