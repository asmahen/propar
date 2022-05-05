<?php

namespace App\Controller;

use App\Repository\OperationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\CssSelector\XPath\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface as TranslationTranslatorInterface;
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
    public function index(ChartBuilderInterface $chartBuilder, OperationsRepository $operationsRepository, TranslationTranslatorInterface $translator) : Response
    {
        //permet de calculer le chiffre d'affaire suivant les catégories des opérations
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
        //calcul le chiffre d'affaire total
        $total = $sommeGrande + $sommeMoyenne +$sommePetite;
        //calcul le nombtre total d'opérations en cours
        $count = count($petite)+count($moyenne)+count($grande);

        // J'instancie ces variables pour pouvoir proposer une traduction anglaise (pour 'Petites Opérations', 'Moyennes Opérations', 'Grandes Opérations')
        $message = $translator->trans('Petites Opérations');
        $message2 = $translator->trans('Moyennes Opérationss');
        $message3 = $translator->trans('Grandes Opérations');

        //création de l'objet chart.js pour afficher le graphique dans chiffres_affaire
        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => [$message, $message2, $message3],
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


        //declaration des variables mois pour la requete sql
        $janvier = "1";
        $fevrier = "2";
        $mars = "3";
        $avril = "4";
        $mai = "5";
        $juin ="6";
        $juillet = "7";
        $aout = "8";
        $septembre = "9";
        $octobre = "10";
        $novembre = "11";
        $decembre = "12";

        //requete sql qui retourne un tableau des somme des opérations par mois à l'année choisie
        $tabJanvier = $operationsRepository->findPrix($janvier, "2022");
        $tabFevrier = $operationsRepository->findPrix($fevrier, "2022");
        $tabMars = $operationsRepository->findPrix($mars, "2022");
        $tabAvril = $operationsRepository->findPrix($avril, "2022");
        $tabMai = $operationsRepository->findPrix($mai, "2022");
        $tabJuin = $operationsRepository->findPrix($juin, "2022");
        $tabJuillet = $operationsRepository->findPrix($juillet, "2022");
        $tabAout = $operationsRepository->findPrix($aout, "2022");
        $tabSeptembre = $operationsRepository->findPrix($septembre, "2022");
        $tabOctobre = $operationsRepository->findPrix($octobre, "2022");
        $tabNovembre = $operationsRepository->findPrix($novembre, "2022");
        $tabDecembre = $operationsRepository->findPrix($decembre, "2022");

        //recupération des valeurs des tableaux précedents
        $sommeJanvier = $tabJanvier[0][1];
        $sommeFevrier = $tabFevrier[0][1];
        $sommeMars = $tabMars[0][1];
        $sommeAvril = $tabAvril[0][1];
        $sommeMai = $tabMai[0][1];
        $sommeJuin = $tabJuin[0][1];
        $sommeJuillet = $tabJuillet[0][1];
        $sommeAout = $tabAout[0][1];
        $sommeSeptembre = $tabSeptembre[0][1];
        $sommeOctobre = $tabOctobre[0][1];
        $sommeNovembre = $tabNovembre[0][1];
        $sommeDecembre = $tabDecembre[0][1];

        $CATrim1 = $sommeJanvier + $sommeFevrier + $sommeMars;
        $CATrim2 = $sommeAvril + $sommeMai + $sommeJuin;
        $CaTrim3 = $sommeJuillet + $sommeAout + $sommeSeptembre;
        $CATrim4 = $sommeOctobre + $sommeNovembre + $sommeDecembre;
        $trimestre1 = "Trimestre 1 : $CATrim1 €";
        $trimestre2 = "Trimestre 2 : $CATrim2 €";
        $trimestre3 = "Trimestre 3 : $CaTrim3 €";
        $trimestre4 = "Trimestre 4 : $CATrim4 €";

        //création de l'objet chart.js pour afficher le graphique dans chiffres_affaire
        $chart2 = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart2->setData([
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octrobre', 'Novembre', 'Decembre'],
            'datasets' => [
                [
                    'backgroundColor' => ['#6cc3d5','#6cc3d5','#6cc3d5','#ffce67','#ffce67','#ffce67', '#f3969a','#f3969a','#f3969a', '#ff700','#ff700','#ff700'],
                    'borderColor' => 'black',
                    'data' => [$sommeJanvier, $sommeFevrier, $sommeMars, $sommeAvril, $sommeMai, $sommeJuin, $sommeJuillet, $sommeAout, $sommeSeptembre, $sommeOctobre, $sommeNovembre, $sommeDecembre],
                    'hoverOffset' => 4,
                    'borderWidth' => 2,

                ],
            ],
        ]);

        $chart2->setOptions([
            'animation.animateScale'=>true,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'subtitle' => [
                    'display' => true,
                    'text' => "$trimestre1 | $trimestre2 | $trimestre3 | $trimestre4",
                    'position' => 'bottom',
                    'font' => [
                        'size' => 16,
                        'weight' => 'bold',
                    ],
                    'padding' => [
                        'top' => 30,
                    ]
                ],
                'title' => [
                    'display' => true,
                    'text' => "Chiffre d'affaires des opérations terminées par mois ",
                    'padding' => [
                        'top' => 10,
                        'bottom' => 30,
                    ],
                    'font' => [
                        'size' => 20,
                        ],

                ]
            ]

        ]);

        return $this->render('chiffre_affaires/index.html.twig', [
            'chart' => $chart,
            'chart2' => $chart2,
            'total' => $total,
            'nbOpTermine' => $count,
        ]);
    }
}
