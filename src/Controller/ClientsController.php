<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_USER")
 * @Route("/clients")
 */
class ClientsController extends AbstractController
{
    /**
     * @Route("/", name="app_clients_index", methods={"GET"})
     */
    public function index(ClientsRepository $clientsRepository): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $clientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_clients_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClientsRepository $clientsRepository): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);

        $form->handleRequest($request);
        /* 1°- Pour ajouter des infos en bdd sans passer par les champs créé automatiquement par symfony lors du make:form
        déclarer la variable adresse et faire appel à la variable symfony request qui est un objet qui stock l'ensemble des infos de notre formulaire ds ce cas là
        dans l'objet request je vais chercher un clé qui s'appelle request et dans cette clé je vais récupérer la clé 'adresse'*/
        $adresse = $request->request->get("adresse");


        if ($form->isSubmitted() && $form->isValid()) {

            /* 2°- j'appelle setAdresse du nouveau client instancié auparavant et lui affecte $adresse qui stock l'adresse précédement récupérée*/
            $client->setAdresse($adresse);
            $clientsRepository->add($client);
            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_clients_show", methods={"GET"})
     */
    public function show(Clients $client): Response
    {
        return $this->render('clients/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_clients_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Clients $client, ClientsRepository $clientsRepository): Response
    {
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientsRepository->add($client);
            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_clients_delete", methods={"POST"})
     */
    public function delete(Request $request, Clients $client, ClientsRepository $clientsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientsRepository->remove($client);
        }

        return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
    }
}
