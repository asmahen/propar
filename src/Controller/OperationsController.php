<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Operations;
use App\Entity\Users;
use App\Form\ClientsType;
use App\Form\OperationsType;
use App\Repository\ClientsRepository;
use App\Repository\OperationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_USER")
 * @Route("/operations")
 */
class OperationsController extends AbstractController
{

    /**
     * @Route("/", name="app_operations_index", methods={"GET"})
     */
    public function index(OperationsRepository $operationsRepository): Response
    {
        return $this->render('operations/index.html.twig', [
            'operations' => $operationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/mesoperations", name="app_operations_mesOperations")
     */
    public function mesOperations (OperationsRepository $operationsRepository)
    {
        return $this->render('operations/mesOperations.html.twig', [
            'operations' => $operationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/operationsdétails", name="app_operations_détails")
     */
    public function operationDetail (OperationsRepository $operationsRepository)
    {
        return $this->render('operations/operationsDetails.html.twig', [
            'operations' => $operationsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="app_operations_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OperationsRepository $operationsRepository, ClientsRepository $clientsRepository, EntityManagerInterface $em): Response
    {
        // instancie un nouvel objet Operation
        $operation = new Operations();

        // appel de la variable user de symfony
        $user = $this->getUser();

        // modification du status de l'operation en non disponible et ajout de la date de création à la date du jour
        $operation->setStatus(false)
            ->setAbort(false)
        ->setCreatedAt(new \DateTime('now'));

        // pour récupérer le nombre total d'opération pr l'utilisateur connecté
        $op = $operationsRepository->findNbOperation($user);

        // pour calculer la totalité des opérations
        $compte = count($op);
        $countMessage = $compte+1;

        //conditions qui permet de savoir si le nombre max d'opérations est atteint
        if (($user->getRoles() == ['ROLE_APPRENTI','ROLE_USER'] and $compte < 1 )
            OR($user->getRoles() == ['ROLE_SENIOR','ROLE_USER'] and $compte < 3 )
            OR ($user->getRoles() == ['ROLE_EXPERT','ROLE_USER'] and $compte < 5 )
        ){

        // création du formulaire
        $form = $this->createForm(OperationsType::class, $operation);

        $form->handleRequest($request);

        // soumission et validation des données sur le formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $operationsRepository->add($operation);
            $operation->setUsers($this->getUser());
            $em->persist($operation);
            $em->flush();
            // conditions qui affiche une notification sur le nombre d'opérations prises pour la persone connectée
            if ($user->getRoles() == ['ROLE_APPRENTI','ROLE_USER'] ) {
                $this->addFlash('info', "Vous avez pris $countMessage opération sur 1");
            }
            if ($user->getRoles() == ['ROLE_SENIOR','ROLE_USER'] ) {
                $this->addFlash('info', "Vous avez pris $countMessage opérations sur 3");
            }
            if ($user->getRoles() == ['ROLE_EXPERT','ROLE_USER'] ) {
                $this->addFlash('info', "Vous avez pris $countMessage opérations sur 5");
            }
            return $this->redirectToRoute('app_operations_mesOperations');
        }

            // instanciation du nouveau client pour le formulaire de la modale ajout client
            $client = new Clients();

            // création du formulaire client
            $form2 = $this->createForm(ClientsType::class, $client);

            $form2->handleRequest($request);

            /* 1°- Pour ajouter des infos en bdd sans passer par les champs créés automatiquement par symfony lors du make:form
            déclarer la variable adresse et faire appel à la variable symfony request qui est un objet qui stock l'ensemble des infos de notre formulaire ds ce cas-là
            dans l'objet request je vais chercher une clé qui s'appelle request et dans cette clé je vais récupérer la clé 'adresse'*/

            $adresse = $request->request->get("adresse");

            // soumission et validation du formulaire client
            if ($form2->isSubmitted() && $form2->isValid()) {

                /* 2°- j'appelle setAdresse du nouveau client instancié auparavant et lui affecte $adresse qui stock l'adresse précédemment récupérée*/
                $client->setAdresse($adresse);


                $clientsRepository->add($client);
                return $this->redirectToRoute('app_operations_new', [], Response::HTTP_SEE_OTHER);
            }

        return $this->render('operations/new.html.twig', [
            'operation' => $operation,
            'client' => $client,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
        ]);
    }  else
        {

            $this->addFlash('error', "Vous ne pouvez plus prendre d'opérations");
            return $this->redirectToRoute('app_operations_index');
        }
        }


    /**
     * @Route("/{id}/edit", name="app_operations_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Operations $operation, OperationsRepository $operationsRepository): Response
    {
        $form = $this->createForm(OperationsType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operationsRepository->add($operation);
            return $this->redirectToRoute('app_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operations/edit.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="app_operations_supprimer", methods={"GET", "POST"})
     */
    public function supprimer(Request $request, Operations $operation, EntityManagerInterface $em): Response
    {
        $operation->setStatus(true)
            ->setAbort(true)
            ->setAbortedAt(new \DateTime('now'));

        $em->persist($operation);
        $em->flush();

        $this->addFlash('error', "Opération supprimée");
        return $this->redirectToRoute('app_operations_index');
    }

    /**
     * @Route("/{id}/terminer", name="app_operations_terminer", methods={"GET", "POST"})
     */
    public function terminer(Request $request, Operations $operation, EntityManagerInterface $em): Response
    {
       $operation->setStatus(true)
       ->setFinishAt(new \DateTime('now'));

       $em->persist($operation);
       $em->flush();

       $this->addFlash('finish', "Opération terminée");
       return $this->redirectToRoute('app_operations_index');
    }

    /**
     * @Route("/{id}", name="app_operations_delete", methods={"POST"})
     */
    public function delete(Request $request, Operations $operation, OperationsRepository $operationsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->request->get('_token'))) {
            $operationsRepository->remove($operation);
        }

        return $this->redirectToRoute('app_operations_index', [], Response::HTTP_SEE_OTHER);
    }


}
