<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ClientsRepository;
use App\Repository\OperationsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="app_home", methods= {"GET"})
     */

    public function index(OperationsRepository $operationsRepository, UsersRepository $usersRepository,ClientsRepository $clientsRepository, CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'operations'=> $operationsRepository->findAll(),
            'users' => $usersRepository->findAll(),
            'clients' =>$clientsRepository->findAll(),
            'categories'=>$categoriesRepository->findAll(),
        ]);
    }
}
