<?php

namespace App\Tests;

use App\Entity\Clients;
use PHPUnit\Framework\TestCase;

class ClientsUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $client = new Clients();

        $client ->setNomSociete('Societe Test')
                ->setNom('NomTest')
                ->setPrenom('PrenomTest');

        $this->assertTrue($client->getNomSociete() === 'Societe Test');
        $this->assertTrue($client->getNom() === 'NomTest');
        $this->assertTrue($client->getPrenom() === 'PrenomTest');


    }
    public function testIsFalse(): void
    {
        $client = new Clients();

        $client ->setNomSociete('Societe Test')
                ->setNom('NomTest')
                ->setPrenom('PrenomTest');

        $this->assertFalse($client->getNomSociete() === 'false');
        $this->assertFalse($client->getNom() === 'false');
        $this->assertFalse($client->getPrenom() === 'false');


    }

        public function testIsEmpty(): void
    {
        $client = new Clients();

        $this->assertEmpty($client->getNomSociete());
        $this->assertEmpty($client->getNom());
        $this->assertEmpty($client->getPrenom());
    }

}
