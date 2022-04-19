<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Créer 3 catégories fakées
        // Catégorie 1
            $categorie1 = new Categories();
            $categorie1 -> setNom("Grosse")
                        -> setPrix(10000);
            
            $manager->persist($categorie1);

        // Catégorie 2
            $categorie2 = new Categories();
            $categorie2 -> setNom("Moyenne")
                        -> setPrix(2500);

             $manager->persist($categorie2);

             // Catégorie 3
            $categorie3 = new Categories();
            $categorie3 -> setNom("Petite")
                        -> setPrix(1000);

             $manager->persist($categorie3);
             
             
             $manager->flush();
        }

        
    }

