<?php

namespace App\Controller;

use App\Repository\OperationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

    /**
    * @isGranted("ROLE_EXPERT", message="Vous n'avez pas accès à cette session")
    */
class ChiffreAffairesController extends AbstractController
{
    /**
    * @Route("/chiffreAffaires", name="app_chiffre_affaires")
    */
    public function index(ChartBuilderInterface $chartBuilder, OperationsRepository $operationsRepository) : Response
    {

        $petite = $operationsRepository->findPetiteOperation();
        $sommePetite = 0;
        foreach ($petite as $value) {
        $sommePetite= $sommePetite + array_sum($petite[0]);
        }
        $moyenne = $operationsRepository->findMoyenneOperation();
        $sommeMoyenne = 0;
        foreach ($moyenne as $value) {
            $sommeMoyenne= $sommeMoyenne + array_sum($moyenne[0]);
        }
        $grande = $operationsRepository->findGrandeOperation();
        $sommeGrande = 0;
        foreach ($grande as $value) {
            $sommeGrande= $sommeGrande + array_sum($grande[0]);
        }

        $total = $sommeGrande + $sommeMoyenne +$sommePetite;


        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => ['Petites Opérations', 'Moyennes Opérations', 'Grandes Opérations'],
            'datasets' => [
                [
                    'label' => 'Points',
                    'backgroundColor' => ['#6cc3d5','#ffce67', '#f3969a'],
                    'borderColor' => 'black',
                    'data' => [$sommePetite, $sommeMoyenne, $sommeGrande],
                    'hoverOffset' => 4,
                    'borderWidth' => 2,

                ],
            ],
        ]);

        $chart->setOptions([
            'animation.animateScale'=>true,

        ]);

        return $this->render('chiffre_affaires/index.html.twig', [
            'chart' => $chart,
            'total' => $total,
        ]);
    }
}
