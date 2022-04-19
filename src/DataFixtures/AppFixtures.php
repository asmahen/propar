<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    //injection de dependances pour encoder/hasher le mot de passe sur la base
    public function __construct(UserPasswordEncoderInterface $encoder){

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        //création de l'expert
        $expert= new Users();
        //creation du mot de pass hasher
        $hash = $this->encoder->encodePassword($expert, "Password");
        $expert->setEmail("admin@gmail.com")
            ->setRoles(['ROLE_EXPERT'])
            ->setPassword($hash)
            ->setNom('nomExpert')
            ->setPrenom('prenomExpert');

        $manager->persist($expert);


        //création de l'apprenti
        for ($i = 1; $i <= 3; $i++) {
            $apprenti = new Users();
            $hash = $this->encoder->encodePassword($apprenti, "Password");
            $apprenti->setEmail("apprenti$i@gmail.com")
                ->setRoles(['ROLE_APPRENTI'])
                ->setPassword($hash)
            ->setNom("nomApprenti$i")
                ->setPrenom("prenomApprenti$i");
            $manager->persist($apprenti);
        }

        //création du senior
        for ($i = 1; $i <= 5; $i++) {
            $senior = new Users();
            $hash = $this->encoder->encodePassword($senior, "Password");
            $senior->setEmail("senior$i@gmail.com")
                ->setRoles(['ROLE_SENIOR'])
                ->setPassword($hash)
                ->setNom("nomSenior$i")
                ->setPrenom("prenomSenior$i");
            $manager->persist($senior);
        }

        $manager->flush();
    }
}
