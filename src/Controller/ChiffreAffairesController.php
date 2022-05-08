<?php

namespace App\Controller;

use App\Form\OperationsType;
use App\Repository\OperationsRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\CssSelector\XPath\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface as TranslationTranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Request;


/**
 * @isGranted("ROLE_EXPERT", message="Vous n'avez pas accès à cette session")
 */
class ChiffreAffairesController extends AbstractController
{
    /**
     * @Route("/chiffreAffaires", name="app_chiffre_affaires",  methods={"GET", "POST"})
     */
    public function index(ChartBuilderInterface $chartBuilder, OperationsRepository $operationsRepository, TranslationTranslatorInterface $translator, Request $request): Response
    {
        //année que l'on souhaite afficher


        $annee = $request->request->get('annee');
        $tabAnnee = $operationsRepository->findAnneeMin();
        $anneeMin = $tabAnnee[0]['year'];

        //condition si année non soumis et pas de valeur par défaut se met à l'année actuelle
        if ($annee == "") {
            $startAnnee = date('Y');
            $this->addFlash('info', "Année $startAnnee apparait par défaut");
            $this->addFlash('info', "Données disponibles à partir de l'année $anneeMin");
            $annee = date('Y');
        }

        //condition si année selectionnée est supérieures à l'année actuelle
        if ($annee > date('Y')) {
            $this->addFlash('error', "Année $annee : no futur - Ou est Sarah Connor");
            $annee = date('Y');

        }

        //calcul du nombres d'opérations en cours par catégories
        $petiteEC = $operationsRepository->findPetiteOperationEC($annee);
        $countPetiteEC = count($petiteEC);
        $moyenneEC = $operationsRepository->findMoyenneOperationEC($annee);
        $countMoyenneEC = count($moyenneEC);
        $grandeEC = $operationsRepository->findGrandeOperationEC($annee);
        $countGrandeEC = count($grandeEC);

        //total du nombres d'opérations en cours
        $totalOpEC = $countPetiteEC + $countMoyenneEC + $countGrandeEC;


        //condition si pas de donnèes dans l'année saisie ou si format incorrect
       if ( $totalOpEC == null) {
           $startAnnee = date('Y');
           $this->addFlash('warning', "Aucunes données de disponible pour l'année saisie ou format année invalide, l'année $startAnnee apparait par défaut");
           $annee = '2022';
       } else {
           $this->addFlash('success', "Chiffres disponibles pour l'année $annee");
       }



        //calcul du nombre et de la somme des opérations terminées par catégories
        $petite = $operationsRepository->findPetiteOperation($annee);
        $sommePetite = 0;
        foreach ($petite as $value) {
            $sommePetite = $sommePetite + array_sum($petite[0]);
        }
        $moyenne = $operationsRepository->findMoyenneOperation($annee);
        $sommeMoyenne = 0;
        foreach ($moyenne as $value) {
            $sommeMoyenne = $sommeMoyenne + array_sum($moyenne[0]);
        }
        $grande = $operationsRepository->findGrandeOperation($annee);
        $sommeGrande = 0;
        foreach ($grande as $value) {
            $sommeGrande = $sommeGrande + array_sum($grande[0]);
        }
        //calcul le chiffre d'affaire total
        $total = $sommeGrande + $sommeMoyenne + $sommePetite;

        //calcul le nombtre total d'opérations terminées
        $count = count($petite) + count($moyenne) + count($grande);

        // J'instancie ces variables pour pouvoir proposer une traduction anglaise (pour 'Petites Opérations', 'Moyennes Opérations', 'Grandes Opérations')
        $message = $translator->trans('Petites Opérations');
        $message2 = $translator->trans('Moyennes Opérations');
        $message3 = $translator->trans('Grandes Opérations');

        //création de l'objet chart.js pour afficher le graphique en tarte des opérations terminées par catégories
        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => [$message, $message2, $message3],
            'datasets' => [
                [
                    'label' => 'Points',
                    'backgroundColor' => ['#6cc3d5', '#ffce67', '#f3969a'],
                    'borderColor' => 'black',
                    'data' => [$sommePetite, $sommeMoyenne, $sommeGrande],
                    'hoverOffset' => 4,
                    'borderWidth' => 1,

                ],
            ],
        ]);

        $chart->setOptions([
            'animation.animateScale' => true,

        ]);


        //declaration des variables mois pour la requete sql
        $mois1 = "1";
        $mois2 = "2";
        $mois3 = "3";
        $mois4 = "4";
        $mois5 = "5";
        $mois6 = "6";
        $mois7 = "7";
        $mois8 = "8";
        $mois9 = "9";
        $mois10 = "10";
        $mois11 = "11";
        $mois12 = "12";


        //requete sql qui retourne un tableau des sommes des opérations par mois à l'année choisie

        $tabJanvier = $operationsRepository->findPrix($mois1, $annee);
        $tabFevrier = $operationsRepository->findPrix($mois2, $annee);
        $tabMars = $operationsRepository->findPrix($mois3, $annee);
        $tabAvril = $operationsRepository->findPrix($mois4, $annee);
        $tabMai = $operationsRepository->findPrix($mois5, $annee);
        $tabJuin = $operationsRepository->findPrix($mois6, $annee);
        $tabJuillet = $operationsRepository->findPrix($mois7, $annee);
        $tabAout = $operationsRepository->findPrix($mois8, $annee);
        $tabSeptembre = $operationsRepository->findPrix($mois9, $annee);
        $tabOctobre = $operationsRepository->findPrix($mois10, $annee);
        $tabNovembre = $operationsRepository->findPrix($mois11, $annee);
        $tabDecembre = $operationsRepository->findPrix($mois12, $annee);

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

        // J'instancie ces variables pour pouvoir proposer une traduction anglaise
        $t1 = $translator->trans('Trimestre 1');
        $t2 = $translator->trans('Trimestre 2');
        $t3 = $translator->trans('Trimestre 3');
        $t4 = $translator->trans('Trimestre 4');

        //affichage des trimestres pour le graphiques générales
        $trimestre1 = "$t1 : $CATrim1 €";
        $trimestre2 = "$t2 : $CATrim2 €";
        $trimestre3 = "$t3 : $CaTrim3 €";
        $trimestre4 = "$t4 : $CATrim4 €";

        // J'instancie ces variables pour pouvoir proposer une traduction anglaise
        $mois1 = $translator->trans('Janvier');
        $mois2 = $translator->trans('Février');
        $mois3 = $translator->trans('Mars');
        $mois4 = $translator->trans('Avril');
        $mois5 = $translator->trans('Mai');
        $mois6 = $translator->trans('Juin');
        $mois7 = $translator->trans('Juillet');
        $mois8 = $translator->trans('Août');
        $mois9 = $translator->trans('Septembre');
        $mois10 = $translator->trans('Octobre');
        $mois11 = $translator->trans('Novembre');
        $mois12 = $translator->trans('Décembre');

        //création de l'objet chart.js pour afficher le graphique dans chiffres_affaire de l'année
        $chart2 = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart2->setData([
            'labels' => [$mois1, $mois2, $mois3, $mois4, $mois5, $mois6, $mois7, $mois8, $mois9, $mois10, $mois11, $mois12],
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
            'tabAnnee' => $annee,


        ]);
    }
}
