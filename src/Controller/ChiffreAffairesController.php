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
        //année que l'on souhaite afficher (pas terminé)
        $annee = "2021";

        //calcul du nombres d'opérations en cours par catégories
        $petiteEC = $operationsRepository->findPetiteOperationEC($annee);
        $countPetiteEC = count($petiteEC);
        $moyenneEC = $operationsRepository->findMoyenneOperationEC($annee);
        $countMoyenneEC = count($moyenneEC);
        $grandeEC = $operationsRepository->findGrandeOperationEC($annee);
        $countGrandeEC = count($grandeEC);
        //total du nombres d'opérations en cours
        $totalOpEC = $countPetiteEC + $countMoyenneEC + $countGrandeEC;


        //calcul du nombre et de la somme des opérations terminées par catégories
        $petite = $operationsRepository->findPetiteOperation($annee);
        $sommePetite = 0;
        foreach ($petite as $value) {
        $sommePetite= $sommePetite + array_sum($petite[0]);
        }
        $moyenne = $operationsRepository->findMoyenneOperation($annee);
        $sommeMoyenne = 0;
        foreach ($moyenne as $value) {
            $sommeMoyenne= $sommeMoyenne + array_sum($moyenne[0]);
        }
        $grande = $operationsRepository->findGrandeOperation($annee);
        $sommeGrande = 0;
        foreach ($grande as $value) {
            $sommeGrande= $sommeGrande + array_sum($grande[0]);
        }
        //calcul le chiffre d'affaire total
        $total = $sommeGrande + $sommeMoyenne +$sommePetite;

        //calcul le nombtre total d'opérations terminées
        $count = count($petite)+count($moyenne)+count($grande);

        // J'instancie ces variables pour pouvoir proposer une traduction anglaise (pour 'Petites Opérations', 'Moyennes Opérations', 'Grandes Opérations')
        $message = $translator->trans('Petites Opérations');
        $message2 = $translator->trans('Moyennes Opérationss');
        $message3 = $translator->trans('Grandes Opérations');

        //création de l'objet chart.js pour afficher le graphique en tarte des opérations terminées par catégories
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
                    'borderWidth' => 1,

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

        //requete sql qui retourne un tableau des sommes des opérations par mois à l'année choisie
        $tabJanvier = $operationsRepository->findPrix($janvier, $annee);
        $tabFevrier = $operationsRepository->findPrix($fevrier, $annee);
        $tabMars = $operationsRepository->findPrix($mars, $annee);
        $tabAvril = $operationsRepository->findPrix($avril, $annee);
        $tabMai = $operationsRepository->findPrix($mai, $annee);
        $tabJuin = $operationsRepository->findPrix($juin, $annee);
        $tabJuillet = $operationsRepository->findPrix($juillet, $annee);
        $tabAout = $operationsRepository->findPrix($aout, $annee);
        $tabSeptembre = $operationsRepository->findPrix($septembre, $annee);
        $tabOctobre = $operationsRepository->findPrix($octobre, $annee);
        $tabNovembre = $operationsRepository->findPrix($novembre, $annee);
        $tabDecembre = $operationsRepository->findPrix($decembre, $annee);

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

        //somme des opérations terminées par trimestre
        $CATrim1 = $sommeJanvier + $sommeFevrier + $sommeMars;
        $CATrim2 = $sommeAvril + $sommeMai + $sommeJuin;
        $CaTrim3 = $sommeJuillet + $sommeAout + $sommeSeptembre;
        $CATrim4 = $sommeOctobre + $sommeNovembre + $sommeDecembre;

        //somme des opérations terminées par semestre
        $CASemestre1 = $CATrim1 + $CATrim2;
        $CASemestre2 = $CaTrim3 + $CATrim4;

        //affichage des trimestres pour le graphiques générales
        $trimestre1 = "Trimestre 1 : $CATrim1 €";
        $trimestre2 = "Trimestre 2 : $CATrim2 €";
        $trimestre3 = "Trimestre 3 : $CaTrim3 €";
        $trimestre4 = "Trimestre 4 : $CATrim4 €";

        //création de l'objet chart.js pour afficher le graphique dans chiffres_affaire de l'année
        $chart2 = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart2->setData([
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octrobre', 'Novembre', 'Decembre'],
            'datasets' => [
                [
                    'backgroundColor' => '#6cc3d5',
                    'data' => [$sommeJanvier, $sommeFevrier, $sommeMars, $sommeAvril, $sommeMai, $sommeJuin, $sommeJuillet, $sommeAout, $sommeSeptembre, $sommeOctobre, $sommeNovembre, $sommeDecembre],
                    'hoverOffset' => 4,

                ],
            ],
        ]);

        $chart2->setOptions([
            'barThickness' => 25,
            'scales' => [
                'padding' => 0,
                'x' => [
                    'grid' => [
                        'display' => false,
                    ]
                ],
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
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
            ]

        ]);

        return $this->render('chiffre_affaires/index.html.twig', [
            'chart' => $chart,
            'chart2' => $chart2,
            'total' => $total,
            'nbOpTermine' => $count,
            'annee' => $annee,
            'semestre1' => $CASemestre1,
            'semestre2' => $CASemestre2,
            'petiteEC' => $countPetiteEC,
            'moyenneEC' => $countMoyenneEC,
            'grandeEC' => $countGrandeEC,
            'totalEC' => $totalOpEC,

        ]);
    }
}
