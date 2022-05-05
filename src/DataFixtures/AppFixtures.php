<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Clients;
use App\Entity\Categories;
use App\Entity\Operations;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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
        $faker = \Faker\Factory::create('fr_FR');

        //création des collaborateurs fakés

        for ($i=1; $i <= 5; $i++) {
        $admin= new Users();
        $hash = $this->encoder->encodePassword($admin, "Password");
        $admin->setNom($faker->lastName)
            ->setPrenom($faker->firstName
            
            )
            ->setPassword("$hash")
            ->setEmail($faker->email)
            ->setRoles(['ROLE_EXPERT']);
        $manager->persist($admin);
        }

        for ($i=1; $i <= 5; $i++) {
            $senior = new Users();
            $hash = $this->encoder->encodePassword($senior, "Password");
            $senior->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setPassword("$hash")
                ->setEmail($faker->email)
                ->setRoles(['ROLE_SENIOR']);
            $manager->persist($senior);
        }

        for ($i=1; $i <= 5; $i++) {
            $apprenti = new Users();
            $hash = $this->encoder->encodePassword($apprenti, "Password");
            $apprenti->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setPassword("$hash")
                ->setEmail($faker->email)
                ->setRoles(['ROLE_APPRENTI']);
            $manager->persist($apprenti);
        }

        //creation de clients

        for ($i=1 ; $i <= 10; $i++) {
            $client = new Clients();
            $client->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setNomSociete($faker->company)
                ->setAdresse($faker->address);
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
        $client1->setPrenom($faker->firstName)
            ->setNom($faker->lastName)
            ->setNomSociete($faker->company)
            ->setAdresse($faker->address);
        $manager->persist($client1);

        //creation des clients
        $client2 = new Clients();
        $client2->setPrenom($faker->firstName)
            ->setNom($faker->lastName)
            ->setNomSociete($faker->company)
            ->setAdresse($faker->address);
        $manager->persist($client2);

        $admin= new Users();
        $hash = $this->encoder->encodePassword($admin, "Password");
        $admin->setNom($faker->lastName)
            ->setPrenom($faker->firstName)
            ->setPassword("$hash")
            ->setEmail($faker->email)
            ->setRoles(['ROLE_EXPERT']);
        $manager->persist($admin);

        $senior= new Users();
        $hash = $this->encoder->encodePassword($senior, "Password");
        $senior->setNom($faker->lastName)
            ->setPrenom($faker->firstName)
            ->setPassword("$hash")
            ->setEmail($faker->email)
            ->setRoles(['ROLE_SENIOR']);
        $manager->persist($senior);

        $apprenti= new Users();
        $hash = $this->encoder->encodePassword($apprenti, "Password");
        $apprenti->setNom($faker->lastName)
            ->setPrenom($faker->firstName)
            ->setPassword("$hash")
            ->setEmail($faker->email)
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti);


        for ($i=1; $i <= 3; $i++) {
        $operation = new Operations();
        $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
            ->setCategories($petiteCategorie)
            ->setClient($client1)
            ->setUsers($admin)
            ->setTitre($faker->words(2, true))
            ->setStatus(false)
            ->setCreatedAt($faker->dateTimeBetween('-1 years'));

        $manager->persist($operation);

        }

        for ($j=1; $j <= 3; $j++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($moyenneCategorie)
                ->setClient($client2)
                ->setUsers($apprenti)
                ->setTitre($faker->words(2, true))
                ->setStatus(false)
                ->setCreatedAt($faker->dateTimeBetween('-1 years'));
            $manager->persist($operation);
        }
        for ($k=1; $k <= 3; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($grandeCategorie)
                ->setClient($client2)
                ->setUsers($apprenti)
                ->setTitre($faker->words(2, true))
                ->setStatus(false)
                ->setCreatedAt($faker->dateTimeBetween('-1 years'));
            $manager->persist($operation);

        }

        $superAdmin= new Users();
        $hash = $this->encoder->encodePassword($senior, "Password");
        $superAdmin->setNom('Do')
            ->setPrenom('John')
            ->setPassword("$hash")
            ->setEmail('admin@gmail.com')
            ->setRoles(['ROLE_EXPERT']);
        $manager->persist($superAdmin);

        for ($k=1; $k <= 30; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($grandeCategorie)
                ->setClient($client2)
                ->setUsers($superAdmin)
                ->setTitre($faker->words(2, true))
                ->setStatus(true)
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setFinishAt($faker->dateTimeBetween('-2 years'));
            $manager->persist($operation);

        }
        for ($k=1; $k <= 30; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($moyenneCategorie)
                ->setClient($client2)
                ->setUsers($superAdmin)
                ->setTitre($faker->words(2, true))
                ->setStatus(true)
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setFinishAt($faker->dateTimeBetween('-2 years'));
            $manager->persist($operation);

        }
        for ($k=1; $k <= 30; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($petiteCategorie)
                ->setClient($client2)
                ->setUsers($superAdmin)
                ->setTitre($faker->words(2, true))
                ->setStatus(true)
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setFinishAt($faker->dateTimeBetween('-2 years'));
            $manager->persist($operation);

        }

        for ($k=1; $k <= 30; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($grandeCategorie)
                ->setClient($client2)
                ->setUsers($superAdmin)
                ->setTitre($faker->words(2, true))
                ->setStatus(false)
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setFinishAt($faker->dateTimeBetween('-2 years'));
            $manager->persist($operation);

        }
        for ($k=1; $k <= 30; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($moyenneCategorie)
                ->setClient($client2)
                ->setUsers($superAdmin)
                ->setTitre($faker->words(2, true))
                ->setStatus(false)
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setFinishAt($faker->dateTimeBetween('-2 years'));
            $manager->persist($operation);

        }
        for ($k=1; $k <= 30; $k++) {
            $operation = new Operations();
            $operation->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setCategories($petiteCategorie)
                ->setClient($client2)
                ->setUsers($superAdmin)
                ->setTitre($faker->words(2, true))
                ->setStatus(false)
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setFinishAt($faker->dateTimeBetween('-2 years'));
            $manager->persist($operation);

        }





        $manager->flush();
    }
}
