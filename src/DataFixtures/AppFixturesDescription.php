<?php

namespace App\DataFixtures;

use App\Entity\Operations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixturesDescription extends Fixture
{
    protected $encoder;

    //injection de dependances pour encoder/hasher le mot de passe sur la base
    public function __construct(UserPasswordEncoderInterface $encoder){

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $vitre= new Operations();
            $vitre->setDescription("Lavage vitres en hauteur");
            $manager->persist($vitre);
        }

//création opération désinfection des locaux
        for ($i = 1; $i <= 2; $i++) {
            $desinfection= new Operations();
            $desinfection= $this->setDescription("Désinfection locaux");
            $manager->persist($desinfection);
        }

//création opération nettoyage des canapés
        for ($i = 1; $i <= 4; $i++) {
            $canape= new Operations();
            $canape= $this->setDescription("Nettoyage canapés");
            $manager->persist($canape);
        }




        $manager->flush();
    }
}
