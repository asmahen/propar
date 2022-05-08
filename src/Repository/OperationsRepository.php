<?php

namespace App\Repository;

use App\Entity\Operations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;

/**
 * @method Operations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operations[]    findAll()
 * @method Operations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationsRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operations::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Operations $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Operations $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Operations[] Returns an array of Operations objects
    //  */

    //fonction qui retourne un tableau des années des opérations terminées
    public function findAnnee() {
        return $this->createQueryBuilder('o')
        ->select('YEAR(o.finishAt) as year')
            ->andWhere(' o.status = true')
            ->addGroupBy('year')
            ->getQuery()
            ->getResult()
        ;
    }

    //fonction qui retourne un tableau du nombre d'operations en cours et non terminées pour l'utilisateur connecté
    public function findNbOperation($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.Users = :val and o.status = false')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    //fonction qui retourne un tableau du prix des operations terminées petite catégorie
    public function findPetiteOperation($annee = '2022' )
    {
        return $this->createQueryBuilder('o')
            ->select('a.prix')
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->andWhere(" o.status = true and a.nom = 'Petite' and YEAR(o.finishAt) = :annee")
            ->getQuery()
            ->getResult()
            ;
    }
    //fonction qui retourne un tableau du prix des operations terminées moyenne catégorie
    public function findMoyenneOperation($annee = '2022')
    {
        return $this->createQueryBuilder('o')
            ->select('a.prix')
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->andWhere(" o.status = true and a.nom = 'Moyenne' and YEAR(o.finishAt) = :annee")
            ->getQuery()
            ->getResult()
            ;
    }

    //fonction qui retourne un tableau du prix des operations terminées grande catégorie
    public function findGrandeOperation($annee = '2022')
    {
        return $this->createQueryBuilder('o')
            ->select('a.prix')
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->Where(" o.status = true and a.nom = 'Grande' and YEAR(o.finishAt) = :annee ")
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPrix($mois, $annee = '2022')
    {
        return $this->createQueryBuilder('o')
            ->select("SUM(a.prix)")
            ->setParameter('mois', $mois)
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->Where(" o.status = true and YEAR(o.finishAt) = :annee and MONTH(o.finishAt) = :mois")
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPetiteOperationEC($annee = '2022')
    {
        return $this->createQueryBuilder('o')
            ->select('a.prix')
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->andWhere(" o.status = false and a.nom = 'Petite' and YEAR(o.finishAt) = :annee")
            ->getQuery()
            ->getResult()
            ;
    }

    public function findMoyenneOperationEC($annee = '2022')
    {
        return $this->createQueryBuilder('o')
            ->select('a.prix')
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->andWhere(" o.status = false and a.nom = 'Moyenne' and YEAR(o.finishAt) = :annee")
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGrandeOperationEC($annee = '2022')
    {
        return $this->createQueryBuilder('o')
            ->select('a.prix')
            ->setParameter('annee', $annee)
            ->innerJoin(Categories::class, 'a', 'WITH', 'a.id = o.Categories')
            ->andWhere(" o.status = false and a.nom = 'Grande' and YEAR(o.finishAt) = :annee")
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Operations
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
