<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixturesClients extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //création du client
        for ($i = 1; $i <= 10; $i++){
            $clients = new Clients();
            $clients->setNomSociete("nomSociete$i")
                ->setPrenom("prenomClient$i")
                ->setNom("nomClient$i")
                ->setAdresse("adresseClient$i");
            $manager->persist($clients);
        }

        $manager->flush();
    }
}
