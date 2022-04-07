<?php

namespace App\Tests;

use App\Entity\Categories;
use PHPUnit\Framework\TestCase;

class CategoriesUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $categorie = new Categories();

        $categorie->setNom('NomTest');
        $categorie->setPrix(1000);

        $this->assertTrue($categorie->getNom() === 'NomTest');
        $this->assertTrue($categorie->getPrix() === 1000);
    }

    public function testIsFalse(): void
    {
        $categorie = new Categories();

        $categorie->setNom('NomTest');
        $categorie->setPrix(1000);

        $this->assertFalse($categorie->getNom() === 'false');
        $this->assertFalse($categorie->getPrix() === 0);
    }

    public function testIsEmpty(): void
    {
        $categorie = new Categories();

        $this->assertEmpty($categorie->getNom());
        $this->assertEmpty($categorie->getPrix());
    }
}
