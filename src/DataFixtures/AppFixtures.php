<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Clients;
use App\Entity\Operations;
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

        //creation des collaborateurs

        for ($i=1; $i <= 5; $i++) {
        $admin= new Users();
        $hash = $this->encoder->encodePassword($admin, "Password");
        $admin->setNom("admin$i")
            ->setPrenom("prenom$i")
            ->setPassword("$hash")
            ->setEmail("admin$i@gmail.com")
            ->setRoles(['ROLE_EXPERT']);
        $manager->persist($admin);
        }

        for ($i=1; $i <= 5; $i++) {
            $senior = new Users();
            $hash = $this->encoder->encodePassword($senior, "Password");
            $senior->setNom("senior$i")
                ->setPrenom("prenom$i")
                ->setPassword("$hash")
                ->setEmail("senior$i@gmail.com")
                ->setRoles(['ROLE_SENIOR']);
            $manager->persist($senior);
        }

        for ($i=1; $i <= 5; $i++) {
            $apprenti = new Users();
            $hash = $this->encoder->encodePassword($apprenti, "Password");
            $apprenti->setNom("nom$i")
                ->setPrenom("prenom$i")
                ->setPassword("$hash")
                ->setEmail("apprenti$i@gmail.com")
                ->setRoles(['ROLE_APPRENTI']);
            $manager->persist($apprenti);
        }

        //creation de clients

        for ($i=1 ; $i <= 10; $i++) {
            $client = new Clients();
            $client->setPrenom("prenomn$i")
                ->setNom("nom$i")
                ->setNomSociete("societe$i")
                ->setAdresse("adresse$i");
            $manager->persist($client);
        }



        //Gestion des catégories avec opérations
        //création de 3 catégories
        $petiteCategorie = new Categories();
        $petiteCategorie->setNom('Petite')
            ->setPrix(1000);
        $manager->persist($petiteCategorie);

        $moyenneCategorie = new Categories();
        $moyenneCategorie->setNom('Moyenne')
            ->setPrix(2500);

        $manager->persist($moyenneCategorie);

        $grandeCategorie = new Categories();
        $grandeCategorie->setNom('Grande')
            ->setPrix(10000);
        $manager->persist($grandeCategorie);

        //creation des clients
        $client1 = new Clients();
        $client1->setPrenom("prenom1")
            ->setNom("nom1")
            ->setNomSociete("societe1")
            ->setAdresse("adresse1");
        $manager->persist($client1);

        //creation des clients
        $client2 = new Clients();
        $client2->setPrenom("prenom2")
            ->setNom("nom1")
            ->setNomSociete("societe2")
            ->setAdresse("adresse2");
        $manager->persist($client2);

        $admin= new Users();
        $hash = $this->encoder->encodePassword($admin, "Password");
        $admin->setNom("admin2")
            ->setPrenom("prenom2")
            ->setPassword("$hash")
            ->setEmail("adminOp@gmail.com")
            ->setRoles(['ROLE_EXPERT']);
        $manager->persist($admin);

        $senior= new Users();
        $hash = $this->encoder->encodePassword($senior, "Password");
        $senior->setNom("senior2")
            ->setPrenom("prenom2")
            ->setPassword("$hash")
            ->setEmail("seniorOp@gmail.com")
            ->setRoles(['ROLE_SENIOR']);
        $manager->persist($senior);

        $apprenti= new Users();
        $hash = $this->encoder->encodePassword($apprenti, "Password");
        $apprenti->setNom("nom")
            ->setPrenom("prenom")
            ->setPassword("$hash")
            ->setEmail("apprentiOP@gmail.com")
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti);


        for ($i=1; $i <= 3; $i++) {
        $operation = new Operations();
        $operation->setDescription("desciption de l'operation $i")
            ->setCategories($petiteCategorie)
            ->setClient($client1)
            ->setUsers($admin)
            ->setTitre("titre $i ");
        $manager->persist($operation);

        }

        for ($i=1; $i <= 3; $i++) {
            $operation = new Operations();
            $operation->setDescription("desciption de l'operation $i")
                ->setCategories($moyenneCategorie)
                ->setClient($client2)
                ->setUsers($apprenti)
                ->setTitre("titre $i ");
            $manager->persist($operation);

        }
        for ($i=1; $i <= 3; $i++) {
            $operation = new Operations();
            $operation->setDescription("desciption de l'operation $i")
                ->setCategories($grandeCategorie)
                ->setClient($client2)
                ->setUsers($apprenti)
                ->setTitre("titre $i ");
            $manager->persist($operation);

        }

        $manager->flush();
    }
}
