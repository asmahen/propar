<?php

namespace App\Controller;

use App\Entity\Operations;
use App\Entity\Users;
use App\Form\OperationsType;
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
    public function mesOperations(OperationsRepository $operationsRepository)
    {
        return $this->render('operations/mesOperations.html.twig', [
            'operations' => $operationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_operations_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OperationsRepository $operationsRepository, EntityManagerInterface $em): Response
    {
        $operation = new Operations();
        $user = $this->getUser();
        $operation->setStatus(false);
        $op = $operationsRepository->findNbOperation($user);
        $compte = count($op);

        if (($user->getRoles() == ['ROLE_APPRENTI','ROLE_USER'] and $compte < 1)
            OR($user->getRoles() == ['ROLE_SENIOR','ROLE_USER'] and $compte < 3)
            OR ($user->getRoles() == ['ROLE_EXPERT','ROLE_USER'] and $compte < 5)){

        $form = $this->createForm(OperationsType::class, $operation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operationsRepository->add($operation);
            $operation->setUsers($this->getUser());
            $em->persist($operation);
            $em->flush();
            return $this->redirectToRoute('app_operations_index');
        }

        return $this->render('operations/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView()
        ]);
    }  else
        {
            $this->addFlash("warning", "Vous ne pouvez plus prendre d'opérations");
            return $this->redirectToRoute('app_operations_index');
        }
        }


    /**
     * @Route("/{id}", name="app_operations_show", methods={"GET"})
     */
    public function show(Operations $operation): Response
    {
        return $this->render('operations/show.html.twig', [
            'operation' => $operation,
        ]);
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
     * @Route("/{id}/terminer", name="app_operations_terminer", methods={"GET", "POST"})
     */
    public function terminer(Request $request, Operations $operation, EntityManagerInterface $em): Response
    {
       $operation->setStatus(true);
       $em->persist($operation);

       $em->flush();

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
